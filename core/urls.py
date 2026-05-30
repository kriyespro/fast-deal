from django.contrib import admin
from django.urls import path, include
from django.conf import settings
from django.conf.urls.static import static
from django.shortcuts import render

handler404 = lambda request, exception: render(request, '404.jinja', status=404)
handler500 = lambda request: render(request, '500.jinja', status=500)

urlpatterns = [
    path('sd/', admin.site.urls),
    path('admin/', include('control.urls', namespace='control')),
    path('', include('users.urls')),
    path('', include('pages.urls')),
    path('', include('properties.urls')),
    path('', include('billing.urls')),
]

if settings.DEBUG:
    urlpatterns += static(settings.MEDIA_URL, document_root=settings.MEDIA_ROOT)
