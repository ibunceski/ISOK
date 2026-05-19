import asyncio
import logging
from contextlib import asynccontextmanager

import uvicorn
from fastapi import FastAPI
from pydantic import BaseModel
from transformers import pipeline

# Setup logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

# Global variables
model_pipeline = None
model_status = "starting"

# --- Background Loader ---
def load_model_background():
    global model_pipeline, model_status
    try:
        logger.info("Background: Loading Multilingual Model for Macedonian...")
        model_status = "loading"

        # mDeBERTa-v3 is excellent for Macedonian NLI (Natural Language Inference)
        model_pipeline = pipeline(
            "zero-shot-classification",
            model="MoritzLaurer/mDeBERTa-v3-base-mnli-xnli"
        )

        model_status = "ready"
        logger.info("Background: Model ready!")
    except Exception as e:
        model_status = "error"
        logger.error(f"Background: Error loading model: {e}")

@asynccontextmanager
async def lifespan(app: FastAPI):
    # This runs when the server starts
    loop = asyncio.get_event_loop()
    loop.run_in_executor(None, load_model_background)
    yield

app = FastAPI(lifespan=lifespan)

class ReportRequest(BaseModel):
    text: str


@app.post("/analyze")
def analyze_report(request: ReportRequest):
    if model_status != "ready":
        return {"score": -1.0, "error": "Model loading"}

    # --- MACEDONIAN DANGER TAXONOMY ---
    labels = [
        "опасност и закана",
        "самоповредување",
        "тага и депресија",
        "барање помош",
        "секојдневен разговор",
        "позитивно чувство"
    ]

    # PROMPT TWEAK: "изразува" (expresses) often yields stronger Contradiction
    # scores from the NLI model than "е за" (is about).
    macedonian_hypothesis = "Оваа порака изразува {}."

    result = model_pipeline(
        request.text,
        labels,
        multi_label=True,
        hypothesis_template=macedonian_hypothesis
    )

    scores = dict(zip(result['labels'], result['scores']))

    safe_score = max(
        scores.get("секојдневен разговор", 0),
        scores.get("позитивно чувство", 0)
    )

    def process_risk(label, weight):
        raw_score = scores.get(label, 0)

        if raw_score < 0.6:
            return 0.0

        adjusted_score = max(0.0, raw_score - safe_score)

        return adjusted_score * weight

    danger_val = process_risk("опасност и закана", 1.0)
    self_harm_val = process_risk("самоповредување", 1.1)
    distress_val = process_risk("тага и депресија", 0.7)
    help_val = process_risk("барање помош", 0.8)

    final_danger_score = max(danger_val, self_harm_val, distress_val, help_val)

    final_danger_score = min(float(final_danger_score), 1.0)

    return {
        "score": round(final_danger_score, 4),
        "categories": {
            "danger": round(danger_val, 2),
            "self_harm": round(self_harm_val, 2),
            "distress": round(distress_val, 2),
            "asking_for_help": round(help_val, 2),
            "safe_baseline": round(safe_score, 2)
        },
        "detected_language": "mk",
        "action_required": final_danger_score > 0.7
    }

if __name__ == "__main__":
    uvicorn.run(app, host="0.0.0.0", port=8000)