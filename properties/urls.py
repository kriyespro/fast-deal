from django.urls import path
from . import views

urlpatterns = [
    path('listings/', views.ListingsView.as_view(), name='listings'),
    path('property/<int:pk>/', views.PropertyDetailView.as_view(), name='property_detail'),
    path('property/<int:pk>/save/', views.ToggleSaveView.as_view(), name='save_property'),
    path('builder/project/add/', views.AddProjectView.as_view(), name='add_project'),
    path('builder/profile/', views.BuilderProfileView.as_view(), name='builder_profile'),
]
