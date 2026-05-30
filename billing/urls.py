from django.urls import path
from . import views

urlpatterns = [
    path('billing/', views.BillingPlansView.as_view(), name='billing_plans'),
    path('billing/subscribe/<int:plan_id>/', views.SubscribeView.as_view(), name='billing_subscribe'),
    path('billing/payment/<int:subscription_id>/', views.PaymentView.as_view(), name='billing_payment'),
    path('billing/invoices/', views.InvoiceListView.as_view(), name='billing_invoices'),
    path('billing/invoice/<int:pk>/', views.InvoiceDetailView.as_view(), name='billing_invoice'),
]
