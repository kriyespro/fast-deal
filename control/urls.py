from django.urls import path
from . import views

app_name = 'control'
urlpatterns = [
    path('', views.DashboardView.as_view(), name='dashboard'),
    path('users/', views.UserListView.as_view(), name='users'),
    path('users/<int:pk>/ban/', views.BanUserView.as_view(), name='ban_user'),
    path('users/<int:pk>/impersonate/', views.ImpersonateView.as_view(), name='impersonate'),
    path('stop-impersonate/', views.StopImpersonateView.as_view(), name='stop_impersonate'),
    path('stats/', views.StatsCardView.as_view(), name='stats_card'),
    path('activity/', views.ActivityFeedView.as_view(), name='activity_feed'),
]
