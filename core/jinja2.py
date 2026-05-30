from django.templatetags.static import static as _static
from django.urls import reverse as _reverse
from django.contrib import messages
from jinja2 import Environment


def url(name, *args, **kwargs):
    if args:
        return _reverse(name, args=args)
    elif kwargs:
        return _reverse(name, kwargs=kwargs)
    return _reverse(name)


def environment(**options):
    env = Environment(**options)
    env.globals.update({
        'static': _static,
        'url': url,
        'get_messages': messages.get_messages,
    })
    return env
