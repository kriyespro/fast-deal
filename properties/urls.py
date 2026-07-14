from django.urls import path
from . import views

urlpatterns = [
    path('listings/', views.ListingsView.as_view(), name='listings'),
    # pk URL first — old/hardcoded links like /property/1/ redirect to slug URL
    path('property/<int:pk>/', views.PropertyDetailByPkView.as_view(), name='property_detail_pk'),
    path('property/<slug:slug>/', views.PropertyDetailView.as_view(), name='property_detail'),
    path('property/<int:pk>/save/', views.ToggleSaveView.as_view(), name='save_property'),
    path('broker/property/add/', views.AddPropertyView.as_view(), name='add_property'),
    path('broker/property/<int:pk>/images/', views.AddPropertyImagesView.as_view(), name='add_property_images'),
    path('builder/project/add/', views.AddProjectView.as_view(), name='add_project'),
    path('builder/profile/', views.BuilderProfileView.as_view(), name='builder_profile'),
]
