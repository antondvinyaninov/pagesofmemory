#!/bin/bash
echo "=== Checking Laravel logs ==="
ssh root@pagesofmemory.ru "tail -50 /var/www/storage/logs/laravel.log"
