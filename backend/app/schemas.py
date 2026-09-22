from pydantic import BaseModel, Field
from typing import Optional
from datetime import date, time, datetime


# Category schemas
class CategoryBase(BaseModel):
    name: str = Field(..., max_length=100)
    color: str = Field(default="#3B82F6", max_length=7)


class CategoryResponse(CategoryBase):
    id: int
    created_at: datetime

    class Config:
        from_attributes = True


# Task schemas
class TaskBase(BaseModel):
    title: str = Field(..., max_length=255)
    description: Optional[str] = None
    category_id: Optional[int] = None
    date: date
    start_time: time
    end_time: time
    duration: int = Field(..., gt=0, description="Duration in minutes")
    status: str = Field(default="pending", pattern="^(pending|completed)$")


class TaskCreate(TaskBase):
    pass


class TaskUpdate(BaseModel):
    title: Optional[str] = Field(None, max_length=255)
    description: Optional[str] = None
    category_id: Optional[int] = None
    date: Optional[date] = None
    start_time: Optional[time] = None
    end_time: Optional[time] = None
    duration: Optional[int] = Field(None, gt=0)
    status: Optional[str] = Field(None, pattern="^(pending|completed)$")


class TaskResponse(TaskBase):
    id: int
    created_at: datetime
    updated_at: datetime
    category: Optional[CategoryResponse] = None

    class Config:
        from_attributes = True


# Report schemas
class DailySummary(BaseModel):
    date: date
    total_tasks: int
    completed_tasks: int
    pending_tasks: int
    total_duration: int  # in minutes


class WeeklySummary(BaseModel):
    start_date: date
    end_date: date
    daily_summaries: list[DailySummary]
    total_tasks: int
    total_duration: int
