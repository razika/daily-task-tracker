from fastapi import FastAPI
from fastapi.middleware.cors import CORSMiddleware
from .database import engine, Base
from .routes import tasks, categories, reports

# Create database tables
Base.metadata.create_all(bind=engine)

app = FastAPI(
    title="DailyTask Tracker API",
    description="API untuk pencatatan tugas harian dengan durasi waktu",
    version="1.0.0"
)

# CORS middleware
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],  # Allow all origins for development
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

# Include routers
app.include_router(tasks.router)
app.include_router(categories.router)
app.include_router(reports.router)


@app.get("/")
def root():
    return {
        "message": "DailyTask Tracker API",
        "version": "1.0.0",
        "docs": "/docs"
    }


@app.get("/health")
def health_check():
    return {"status": "healthy"}
