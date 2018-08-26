FROM "bitnami/python:latest"
RUN pip install --upgrade pip
RUN pip install python-telegram-bot
RUN pip install requests
COPY bot.py /app/bot.py

CMD ["python","bot.py"]
