FROM python:3.11-slim

# gcc is required to compile some native extensions in the dep tree
RUN apt-get update \
    && apt-get install -y --no-install-recommends gcc \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /app

# Install Python dependencies before copying application code so that this
# expensive layer (downloads torch, transformers, etc.) is cached unless
# requirements.txt changes.
COPY requirements.txt ./
RUN pip install --no-cache-dir -r requirements.txt

# Copy application source
COPY . .

EXPOSE 8000

# --reload is a development-only flag that reloads on file changes; omit it
# in Docker where the filesystem is fixed. --workers 1 avoids loading the
# ML model multiple times (each worker would load its own copy into RAM).
CMD ["uvicorn", "main:app", "--host", "0.0.0.0", "--port", "8000", "--workers", "1"]
