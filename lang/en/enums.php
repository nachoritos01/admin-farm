<?php

return [
    // OrderStatus
    'order_status' => [
        'draft' => 'Draft',
        'pending' => 'Pending',
        'confirmed' => 'Confirmed',
        'in_progress' => 'In Progress',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
    ],

    // OrderPriority
    'order_priority' => [
        'low' => 'Low',
        'normal' => 'Normal',
        'high' => 'High',
        'urgent' => 'Urgent',
    ],

    // HenBatchStatus
    'hen_batch_status' => [
        'active' => 'Active',
        'resting' => 'Resting',
        'molting' => 'Molting',
        'retired' => 'Retired',
        'sold' => 'Sold',
        'quarantine' => 'Quarantine',
    ],

    // HenMovementType
    'hen_movement_type' => [
        'addition' => 'Addition',
        'removal' => 'Removal',
        'death' => 'Death',
        'transfer' => 'Transfer',
        'sale' => 'Sale',
    ],

    // CustomerType
    'customer_type' => [
        'retail' => 'Retail',
        'wholesale' => 'Wholesale',
        'store' => 'Store',
        'restaurant' => 'Restaurant',
        'natural_store' => 'Natural Store',
    ],

    // EggSize
    'egg_size' => [
        'small' => 'Small',
        'medium' => 'Medium',
        'large' => 'Large',
        'extra_large' => 'Extra Large',
        'jumbo' => 'Jumbo',
        'mixed' => 'Mixed',
    ],

    // QualityGrade
    'quality_grade' => [
        'a' => 'Grade A',
        'b' => 'Grade B',
        'c' => 'Grade C',
    ],

    // ExpenseCategory
    'expense_category' => [
        'feed' => 'Feed',
        'medicine' => 'Medicine',
        'equipment' => 'Equipment',
        'packaging' => 'Packaging',
        'labor' => 'Labor',
        'utilities' => 'Utilities',
        'transport' => 'Transport',
        'maintenance' => 'Maintenance',
        'other' => 'Other',
    ],

    // SupplierCategory
    'supplier_category' => [
        'feed' => 'Feed',
        'medicine' => 'Medicine',
        'equipment' => 'Equipment',
        'packaging' => 'Packaging',
        'other' => 'Other',
    ],

    // SupplierStatus
    'supplier_status' => [
        'active' => 'Active',
        'inactive' => 'Inactive',
        'suspended' => 'Suspended',
    ],

    // ShipmentStatus
    'shipment_status' => [
        'scheduled' => 'Scheduled',
        'in_transit' => 'In Transit',
        'delivered' => 'Delivered',
        'cancelled' => 'Cancelled',
    ],

    // HealthRecordType
    'health_record_type' => [
        'vaccination' => 'Vaccination',
        'treatment' => 'Treatment',
        'observation' => 'Observation',
        'mortality' => 'Mortality',
    ],

    // PaymentMethod
    'payment_method' => [
        'cash' => 'Cash',
        'card' => 'Card',
        'transfer' => 'Transfer',
        'other' => 'Other',
        'cash_on_delivery' => 'Cash on Delivery',
    ],

    // DeliveryType
    'delivery_type' => [
        'pickup' => 'Store Pickup',
        'delivery' => 'Home Delivery',
    ],

    // Breed
    'breed' => [
        'rhode_island_red' => 'Rhode Island Red',
        'leghorn' => 'Leghorn',
        'plymouth_rock' => 'Plymouth Rock',
        'araucana' => 'Araucana',
        'other' => 'Other',
    ],

    // DeathCause
    'death_cause' => [
        'natural' => 'Natural',
        'disease' => 'Disease',
        'accident' => 'Accident',
        'unknown' => 'Unknown',
    ],

    // UnitType
    'unit_type' => [
        'tray' => 'Tray (30)',
        'kilogram' => 'Kilogram',
        'piece' => 'Piece',
    ],

    // PurchaseUnit
    'purchase_unit' => [
        'kilogram' => 'Kilogram',
        'liter' => 'Liter',
        'piece' => 'Piece',
        'sack' => 'Sack',
        'ton' => 'Ton',
        'other' => 'Other',
    ],

    // PlanType
    'plan_type' => [
        'starter' => 'Starter',
        'growth' => 'Growth',
        'pro' => 'Pro',
    ],

    // SubscriptionStatus
    'subscription_status' => [
        'active' => 'Active',
        'trialing' => 'Trialing',
        'canceled' => 'Canceled',
        'past_due' => 'Past Due',
        'incomplete' => 'Incomplete',
    ],

    // BillingPeriod
    'billing_period' => [
        'monthly' => 'Monthly',
        'yearly' => 'Yearly',
    ],

    // BillingEventType
    'billing_event_type' => [
        'subscription_started' => 'Subscription Started',
        'plan_changed' => 'Plan Changed',
        'plugin_activated' => 'Plugin Activated',
        'plugin_deactivated' => 'Plugin Deactivated',
        'subscription_cancelled' => 'Subscription Cancelled',
    ],

    // CancellationReason
    'cancellation_reason' => [
        'price' => 'Too expensive',
        'missing_features' => 'Missing features',
        'closed_business' => 'Closed business',
        'competitor' => 'Switched to competitor',
        'other' => 'Other',
    ],

    // LoyaltyTier
    'loyalty_tier' => [
        'bronze' => 'Bronze',
        'silver' => 'Silver',
        'gold' => 'Gold',
        'vip' => 'VIP',
    ],
];
