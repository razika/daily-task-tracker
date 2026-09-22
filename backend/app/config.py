from pydantic_settings import BaseSettings
from functools import lru_cache


class Settings(BaseSettings):
    DATABASE_URL: str = "postgresql://admin:secret123@localhost:5432/dailytask"
    APP_NAME: str = "DailyTask Tracker API"
    DEBUG: bool = True

    class Config:
        env_file = ".env"
        extra = "allow"


@lru_cache()
def get_settings() -> Settings:
    return Settings()
