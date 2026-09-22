from fastapi import APIRouter, Depends, Query
from sqlalchemy.orm import Session
from sqlalchemy import func
from datetime import date, timedelta
from typing import Optional
from app.database import get_db
from app.models import Task
from app.schemas import DailySummary, DailySummary

router = APIRouter(prefix="/api/reports", tags=["reports"])


@router.get("/summary", response_model=DailySummary)
def get_daily_summary(
    date: Optional[date] = Query(None, description="Date for summary (default: today)"),
    db: Session = Depends(get_db)
):
    target_date = date or func.current_date()

    # Get tasks for the date
    tasks = db.query(Task).filter(Task.date == target_date).all()

    total_tasks = len(tasks)
    completed_tasks = sum(1 for t in tasks if t.status == "completed")
    pending_tasks = total_tasks - completed_tasks
    total_duration = sum(t.duration for t in tasks)

    return DailySummary(
        date=target_date,
        total_tasks=total_tasks,
        completed_tasks=completed_tasks,
        pending_tasks=pending_tasks,
        total_duration=total_duration
    )


@router.get("/weekly")
def get_weekly_summary(
    start_date: Optional[date] = Query(None, description="Start date (default: 7 days ago)"),
    end_date: Optional[date] = Query(None, description="End date (default: today)"),
    db: Session = Depends(get_db)
):
    end = end_date or func.current_date()
    start = start_date or (end - timedelta(days=6))

    daily_summaries = []
    current_date = start

    while current_date <= end:
        tasks = db.query(Task).filter(Task.date == current_date).all()
        total_tasks = len(tasks)
        completed_tasks = sum(1 for t in tasks if t.status == "completed")
        pending_tasks = total_tasks - completed_tasks
        total_duration = sum(t.duration for t in tasks)

        daily_summaries.append({
            "date": current_date.isoformat(),
            "total_tasks": total_tasks,
            "completed_tasks": completed_tasks,
            "pending_tasks": pending_tasks,
            "total_duration": total_duration
        })

        current_date += timedelta(days=1)

    total_tasks_all = sum(d["total_tasks"] for d in daily_summaries)
    total_duration_all = sum(d["total_duration"] for d in daily_summaries)

    return {
        "start_date": start.isoformat(),
        "end_date": end.isoformat(),
        "daily_summaries": daily_summaries,
        "total_tasks": total_tasks_all,
        "total_duration": total_duration_all
    }
