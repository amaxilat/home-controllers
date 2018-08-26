import logging

logging.basicConfig(format='%(asctime)s - %(name)s - %(levelname)s - %(message)s', level=logging.INFO)


def info(message):
    logging.log(level=logging.INFO, msg=message)


from telegram.ext import CommandHandler
from telegram.ext import Updater
from telegram.ext import MessageHandler, Filters
import os
import requests

token = os.environ['TELEGRAM_BOT_TOKEN']
lightsUrl = os.environ['LIGHTS_URL']
acUrl = os.environ['AC_URL']
hueUrl = os.environ['HUE_URL']
accepted_users_str = os.environ['ACCEPTED_USERS']
accepted_users = []

for user in accepted_users_str.split(","):
    accepted_users.append(int(user))

info(token)

updater = Updater(token=token)
info("dispatcher")
dispatcher = updater.dispatcher


def start(bot, update):
    bot.send_message(chat_id=update.message.chat_id, text="Welcome home! how can I help you?")


start_handler = CommandHandler('start', start)
dispatcher.add_handler(start_handler)


def intValue(text):
    if text == "on":
        return 1
    else:
        return 0


def lightsSwitch(number, status):
    requests.get(lightsUrl + "?plug=" + str(number) + "&status=" + str(status))


def acSwitch(state):
    if state == 28:
        requests.get(acUrl + "?device=ytf1&command=cool-28-low")
    if state == 1 or state == 26:
        requests.get(acUrl + "?device=ytf1&command=cool-26-low")
    else:
        requests.get(acUrl + "?device=ytf1&command=off")

def hueSwitch(status):
    requests.get(hueUrl+"?hue=3&status="+status)

def doSwitch(bot, chat_id, zone, state):
    replyText = "Switching "
    replyText += str(state)
    replyText += " light zone "
    replyText += zone
    bot.send_message(chat_id=chat_id, text=replyText)
    lightsSwitch(zone, state)
    bot.send_message(chat_id=chat_id, text="Done")


def doSwitchAc(bot, chat_id, state):
    replyText = "Switching AC "
    replyText += state
    bot.send_message(chat_id=chat_id, text=replyText)
    acSwitch(intValue(state))
    bot.send_message(chat_id=chat_id, text="Done")

def doHue(bot, chat_id, state):
    replyText = "Switching Hue"
    replyText += state
    bot.send_message(chat_id=chat_id, text=replyText)
    hueSwitch(state)
    bot.send_message(chat_id=chat_id, text="Done")

def parseMessage(bot, chat_id, text, user):
    parts = text.split(" ")
    if parts[0] == "lights":
        doSwitch(bot, chat_id, parts[1], intValue(parts[2]))
    elif parts[0] == "hue":
        doHue(bot, chat_id, parts[1])
    elif parts[0] == "ac":
        replyText = "Switching "
        acSwitch(intValue(parts[1]))
        replyText += parts[1]
        bot.send_message(chat_id=chat_id, text=replyText)
    elif parts[0] == "goodmorning":
        bot.send_message(chat_id=chat_id, text="Switching ")
    elif parts[0] == "goodnight":
        bot.send_message(chat_id=chat_id, text="Goodnight " + user.first_name + "!")
        doSwitch(bot, chat_id, "1", 0)
        doSwitch(bot, chat_id, "2", 0)
        doSwitch(bot, chat_id, "3", 0)
        doSwitchAc(bot, chat_id, "off")
        doHue(bot, chat_id, "0")
        bot.send_message(chat_id=chat_id, text="All done!")
    else:
        bot.send_message(chat_id=chat_id, text="Sorry, I cannot help you with that. :(")


def echo(bot, update):
    userId = int(update.message.from_user.id)
    if userId in accepted_users:
        info("Accepting user " + str(userId))
        parseMessage(bot, update.message.chat_id, update.message.text.lower(), update.message.from_user)
    else:
        info("Rejecting user " + str(userId))
        bot.send_message(chat_id=update.message.chat_id, text="Sorry I do not know you")


echo_handler = MessageHandler(Filters.text, echo)
dispatcher.add_handler(echo_handler)

updater.start_polling()
