from django.shortcuts import render, redirect, get_object_or_404
from django.contrib.auth.decorators import login_required
from django.utils.decorators import method_decorator
from django.views import View
from django.http import HttpResponse

from .models import Plan, Subscription, Invoice
from .services import (
    get_all_plans, get_user_subscription, get_user_active_plan,
    create_pending_subscription, process_demo_payment, get_user_invoices,
    can_add_listing, can_add_project,
)


@method_decorator(login_required, name='dispatch')
class BillingPlansView(View):
    def get(self, request):
        plans = get_all_plans()
        current_sub = get_user_subscription(request.user)
        active_plan = get_user_active_plan(request.user)
        return render(request, 'pages/billing_plans.jinja', {
            'plans': plans,
            'current_sub': current_sub,
            'active_plan': active_plan,
        })


@method_decorator(login_required, name='dispatch')
class SubscribeView(View):
    def post(self, request, plan_id):
        plan = get_object_or_404(Plan, pk=plan_id, is_active=True)
        if plan.price == 0:
            # Free plan — activate directly, no payment needed
            sub = create_pending_subscription(request.user, plan)
            from .services import activate_subscription, create_invoice
            activate_subscription(sub)
            # No invoice for free plan
            return redirect('billing_plans')

        sub = create_pending_subscription(request.user, plan)
        return redirect('billing_payment', subscription_id=sub.pk)


@method_decorator(login_required, name='dispatch')
class PaymentView(View):
    def get(self, request, subscription_id):
        sub = get_object_or_404(
            Subscription, pk=subscription_id, user=request.user
        )
        if sub.is_active:
            return redirect('billing_invoices')
        return render(request, 'pages/billing_payment.jinja', {'subscription': sub})

    def post(self, request, subscription_id):
        sub = get_object_or_404(
            Subscription, pk=subscription_id, user=request.user
        )
        sub, invoice = process_demo_payment(sub.pk)
        return redirect('billing_invoice', pk=invoice.pk)


@method_decorator(login_required, name='dispatch')
class InvoiceListView(View):
    def get(self, request):
        invoices = get_user_invoices(request.user)
        current_sub = get_user_subscription(request.user)
        return render(request, 'pages/billing_invoices.jinja', {
            'invoices': invoices,
            'current_sub': current_sub,
        })


@method_decorator(login_required, name='dispatch')
class InvoiceDetailView(View):
    def get(self, request, pk):
        invoice = get_object_or_404(Invoice, pk=pk, user=request.user)
        return render(request, 'pages/billing_invoice.jinja', {'invoice': invoice})
