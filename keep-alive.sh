#!/bin/bash
# HealthNexus Keep-Alive - Túnel permanente

LOG="/tmp/tunnel_alive.log"

echo "🚀 Iniciando Keep-Alive..." | tee -a $LOG
date | tee -a $LOG

while true; do
    # Verificar si Laravel está corriendo
    if ! pgrep -f "php artisan serve" > /dev/null; then
        echo "[$(date)] Reiniciando Laravel..." | tee -a $LOG
        php artisan serve --host=127.0.0.1 --port=3001 > /tmp/laravel.log 2>&1 &
        sleep 5
    fi

    # Verificar si cloudflared está corriendo
    if ! pgrep -f cloudflared > /dev/null; then
        echo "[$(date)] Reiniciando túnel..." | tee -a $LOG
        cloudflared tunnel --url http://127.0.0.1:3001 > /tmp/cloudflared.log 2>&1 &
        sleep 10

        # Obtener nueva URL
        URL=$(grep -oP 'https://[a-z0-9-]+\.trycloudflare\.com' /tmp/cloudflared.log | head -1)
        if [ ! -z "$URL" ]; then
            echo "[$(date)] ✅ Túnel activo: $URL" | tee -a $LOG
            echo "$URL" > /tmp/tunnel_url.txt
            # Actualizar .env
            sed -i "s|APP_URL=.*|APP_URL=$URL|" /home/keyyy/HealthNxs/.env
        fi
    fi

    # Verificar que responde
    HTTP=$(curl -s -o /dev/null -w "%{http_code}" http://127.0.0.1:3001 2>/dev/null)
    if [ "$HTTP" != "200" ] && [ "$HTTP" != "302" ]; then
        echo "[$(date)] ⚠️ Laravel no responde (HTTP $HTTP), reiniciando..." | tee -a $LOG
        pkill -f "php artisan serve" 2>/dev/null
        sleep 2
        php artisan serve --host=127.0.0.1 --port=3001 > /tmp/laravel.log 2>&1 &
        sleep 5
    fi

    # Esperar 30 seg antes de verificar de nuevo
    sleep 30
done
