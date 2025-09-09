# Natural Language Date Interpreter

Web application that converts natural language expressions into structured JSON using OpenAI's ChatGPT API.

## Features
- 📅 Date interpretation ("next Tuesday" → `{"date": "2025-01-21"}`)
- 📦 Product descriptions generation
- 📝 Text summarization
- 💾 MySQL history storage
- 🐳 Fully Dockerized

## Prerequisites
- Docker & Docker Compose
- Git
- OpenAI API Key ([Get one here](https://platform.openai.com/api-keys))

## Quick Start

```bash
# 1. Clone repository
git clone https://github.com/yourusername/nlp-date-interpreter.git
cd nlp-date-interpreter

# 2. Configure environment
cp .env.example .env
# Edit .env and add: OPENAI_API_KEY=sk-your-actual-key

# 3. Start application
docker-compose up --build

# 4. Access
# Frontend: http://localhost:3000
# API: http://localhost:8080/api
```

## Project Structure
```
backend/        # PHP 8.1 API
frontend/       # React 18 UI
database/       # MySQL schema
docker-compose.yml
```

## API Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/interpret` | Interpret natural language |
| GET | `/api/history` | Get request history |
| GET | `/api/test` | Test connectivity |

### Example Request
```bash
curl -X POST http://localhost:8080/api/interpret \
  -H "Content-Type: application/json" \
  -d '{"query":"next Monday","type":"date"}'
```

## Environment Variables

Create `.env` file with:
```env
DB_HOST=mysql
DB_NAME=nlp_interpreter
DB_USER=root
DB_PASS=rootpassword
OPENAI_API_KEY=sk-your-actual-key-here
VITE_API_URL=http://localhost:8080/api
```

## Common Commands

```bash
# Start services
docker-compose up -d

# View logs
docker-compose logs -f

# Restart services
docker-compose restart

# Stop everything
docker-compose down

# Complete reset
docker-compose down -v && docker-compose up --build
```

## Ports
- Frontend: `3000`
- Backend: `8080`  
- MySQL: `3306`


