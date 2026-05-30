from django.urls import path
from . import views

urlpatterns = [
    path('', views.HomeView.as_view(), name='home'),
    # listings + property_detail now served by properties app
    path('agents/', views.AgentsView.as_view(), name='agents'),
    path('agent/<int:pk>/', views.AgentDetailView.as_view(), name='agent_detail'),
    path('neighborhoods/', views.NeighborhoodsView.as_view(), name='neighborhoods'),
    path('about/', views.AboutView.as_view(), name='about'),
    path('contact/', views.ContactView.as_view(), name='contact'),
    path('blog/', views.BlogView.as_view(), name='blog'),
    path('blog/<int:pk>/', views.BlogDetailView.as_view(), name='blog_detail'),
    path('dashboard/client/', views.DashboardClientView.as_view(), name='dashboard_client'),
    path('dashboard/broker/', views.DashboardBrokerView.as_view(), name='dashboard_broker'),
    path('dashboard/builder/', views.DashboardBuilderView.as_view(), name='dashboard_builder'),
    path('dashboard/admin/', views.DashboardAdminView.as_view(), name='dashboard_admin'),
    path('cityadmin/listing/<int:pk>/approve/', views.ApproveListingView.as_view(), name='approve_listing'),
    path('cityadmin/listing/<int:pk>/reject/', views.RejectListingView.as_view(), name='reject_listing'),
    path('cityadmin/broker/<int:pk>/verify/', views.VerifyBrokerView.as_view(), name='verify_broker'),
    path('cityadmin/broker/<int:pk>/suspend/', views.SuspendBrokerView.as_view(), name='suspend_broker'),
    path('cityadmin/broker/<int:pk>/reinstate/', views.ReinstateBrokerView.as_view(), name='reinstate_broker'),
]
