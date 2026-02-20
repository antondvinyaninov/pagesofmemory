# Правило: База данных

## Важно

**Проект использует PostgreSQL как локально, так и на продакшене.**

- ❌ НЕ используем SQLite
- ✅ Используем PostgreSQL везде
- ✅ Подключение к удаленной БД: Host: 88.218.121.213, Port: 5432, Database: my_projects

## Синтаксис для PostgreSQL

При работе с базой данных используй PostgreSQL синтаксис:

```sql
-- Отключение триггеров для изменения ID (PostgreSQL)
ALTER TABLE table_name DISABLE TRIGGER ALL;
-- делаем изменения
ALTER TABLE table_name ENABLE TRIGGER ALL;

-- Отключение проверки внешних ключей (PostgreSQL)
SET CONSTRAINTS ALL DEFERRED;

-- НЕ используй SQLite синтаксис
-- ❌ PRAGMA foreign_keys = OFF
```

## Миграции

- Используй типы данных PostgreSQL
- Учитывай особенности PostgreSQL при создании индексов и constraints
