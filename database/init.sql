-- DailyTask Tracker Database Schema
-- PostgreSQL 16

-- Enable UUID extension (optional, for future use)
-- CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

-- Categories table
CREATE TABLE IF NOT EXISTS categories (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    color VARCHAR(7) DEFAULT '#3B82F6',
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- Tasks table
CREATE TABLE IF NOT EXISTS tasks (
    id SERIAL PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    category_id INT REFERENCES categories(id) ON DELETE SET NULL,
    date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    duration INT NOT NULL, -- Duration in minutes
    status VARCHAR(20) DEFAULT 'pending' CHECK (status IN ('pending', 'completed')),
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- Index for faster queries
CREATE INDEX IF NOT EXISTS idx_tasks_date ON tasks(date);
CREATE INDEX IF NOT EXISTS idx_tasks_category ON tasks(category_id);
CREATE INDEX IF NOT EXISTS idx_tasks_status ON tasks(status);

-- Function to automatically update updated_at
CREATE OR REPLACE FUNCTION update_updated_at_column()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = NOW();
    RETURN NEW;
END;
$$ language 'plpgsql';

-- Trigger for categories
CREATE TRIGGER update_categories_updated_at
    BEFORE UPDATE ON categories
    FOR EACH ROW
    EXECUTE FUNCTION update_updated_at_column();

-- Trigger for tasks
CREATE TRIGGER update_tasks_updated_at
    BEFORE UPDATE ON tasks
    FOR EACH ROW
    EXECUTE FUNCTION update_updated_at_column();

-- Seed default categories
INSERT INTO categories (name, color) VALUES
    ('Kerja', '#EF4444'),      -- Red
    ('Pribadi', '#3B82F6'),    -- Blue
    ('Belajar', '#10B981'),    -- Green
    ('Lainnya', '#6B7280')     -- Gray
ON CONFLICT (name) DO NOTHING;

-- Sample data (optional, for testing)
-- Uncomment below to insert sample tasks
/*
INSERT INTO tasks (title, description, category_id, date, start_time, end_time, duration, status) VALUES
    ('Meeting Sprint Planning', 'Diskusi sprint minggu ini', 1, CURRENT_DATE, '09:00', '10:00', 60, 'completed'),
    ('Coding Feature Login', 'Implementasi login page', 1, CURRENT_DATE, '10:30', '12:00', 90, 'completed'),
    ('Olahraga Pagi', 'Lari pagi di taman', 2, CURRENT_DATE, '06:00', '06:30', 30, 'completed'),
    ('Belajar Python', 'Tutorial FastAPI', 3, CURRENT_DATE, '19:00', '20:30', 90, 'pending');
*/
