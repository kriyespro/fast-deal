from django.contrib import admin
from django.urls import path, include, re_path
from django.conf import settings
from django.shortcuts import render
from django.views.static import serve

handler404 = lambda request, exception: render(request, '404.jinja', status=404)
handler500 = lambda request: render(request, '500.jinja', status=500)

urlpatterns = [
    path('sd/', admin.site.urls),
    path('admin/', include('control.urls', namespace='control')),
    path('', include('users.urls')),
    path('', include('pages.urls')),
    path('', include('properties.urls')),
    path('', include('billing.urls')),
    # Media must be served in production too — WhiteNoise only covers STATIC.
    # Host nginx proxies /media/ → Gunicorn; files live on the media_data volume.
    re_path(r'^media/(?P<path>.*)$', serve, {'document_root': settings.MEDIA_ROOT}),
]
