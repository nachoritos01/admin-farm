<?php

return [
    // OrderStatus
    'order_status' => [
        'draft' => 'Borrador',
        'pending' => 'Pendiente',
        'confirmed' => 'Confirmado',
        'in_progress' => 'En Progreso',
        'completed' => 'Completado',
        'cancelled' => 'Cancelado',
    ],

    // OrderPriority
    'order_priority' => [
        'low' => 'Baja',
        'normal' => 'Normal',
        'high' => 'Alta',
        'urgent' => 'Urgente',
    ],

    // HenBatchStatus
    'hen_batch_status' => [
        'active' => 'Activo',
        'resting' => 'En Descanso',
        'molting' => 'En Muda',
        'retired' => 'Retirado',
        'sold' => 'Vendido',
        'quarantine' => 'Cuarentena',
    ],

    // HenMovementType
    'hen_movement_type' => [
        'addition' => 'Adición',
        'removal' => 'Remoción',
        'death' => 'Muerte',
        'transfer' => 'Transferencia',
        'sale' => 'Venta',
    ],

    // CustomerType
    'customer_type' => [
        'retail' => 'Menudeo',
        'wholesale' => 'Mayoreo',
        'store' => 'Tienda',
        'restaurant' => 'Restaurante',
        'natural_store' => 'Tienda Natural',
    ],

    // EggSize
    'egg_size' => [
        'small' => 'Chico',
        'medium' => 'Mediano',
        'large' => 'Grande',
        'extra_large' => 'Extra Grande',
        'jumbo' => 'Jumbo',
        'mixed' => 'Mixto',
    ],

    // QualityGrade
    'quality_grade' => [
        'a' => 'Grado A',
        'b' => 'Grado B',
        'c' => 'Grado C',
    ],

    // ExpenseCategory
    'expense_category' => [
        'feed' => 'Alimento',
        'medicine' => 'Medicina',
        'equipment' => 'Equipo',
        'packaging' => 'Empaque',
        'labor' => 'Mano de Obra',
        'utilities' => 'Servicios',
        'transport' => 'Transporte',
        'maintenance' => 'Mantenimiento',
        'other' => 'Otro',
    ],

    // SupplierCategory
    'supplier_category' => [
        'feed' => 'Alimento',
        'medicine' => 'Medicina',
        'equipment' => 'Equipo',
        'packaging' => 'Empaque',
        'other' => 'Otro',
    ],

    // SupplierStatus
    'supplier_status' => [
        'active' => 'Activo',
        'inactive' => 'Inactivo',
        'suspended' => 'Suspendido',
    ],

    // ShipmentStatus
    'shipment_status' => [
        'scheduled' => 'Programado',
        'in_transit' => 'En Tránsito',
        'delivered' => 'Entregado',
        'cancelled' => 'Cancelado',
    ],

    // HealthRecordType
    'health_record_type' => [
        'vaccination' => 'Vacunación',
        'treatment' => 'Tratamiento',
        'observation' => 'Observación',
        'mortality' => 'Mortalidad',
    ],

    // PaymentMethod
    'payment_method' => [
        'cash' => 'Efectivo',
        'card' => 'Tarjeta',
        'transfer' => 'Transferencia',
        'other' => 'Otro',
        'cash_on_delivery' => 'Contra Entrega',
    ],

    // DeliveryType
    'delivery_type' => [
        'pickup' => 'Recoger en Tienda',
        'delivery' => 'Entrega a Domicilio',
    ],

    // Breed
    'breed' => [
        'rhode_island_red' => 'Rhode Island Red',
        'leghorn' => 'Leghorn',
        'plymouth_rock' => 'Plymouth Rock',
        'araucana' => 'Araucana',
        'other' => 'Otra',
    ],

    // DeathCause
    'death_cause' => [
        'natural' => 'Natural',
        'disease' => 'Enfermedad',
        'accident' => 'Accidente',
        'unknown' => 'Desconocida',
    ],

    // UnitType
    'unit_type' => [
        'tray' => 'Reja (30)',
        'kilogram' => 'Kilogramo',
        'piece' => 'Pieza',
    ],

    // PurchaseUnit
    'purchase_unit' => [
        'kilogram' => 'Kilogramo',
        'liter' => 'Litro',
        'piece' => 'Pieza',
        'sack' => 'Costal',
        'ton' => 'Tonelada',
        'other' => 'Otro',
    ],

    // PlanType
    'plan_type' => [
        'starter' => 'Starter',
        'growth' => 'Growth',
        'pro' => 'Pro',
    ],

    // SubscriptionStatus
    'subscription_status' => [
        'active' => 'Activa',
        'trialing' => 'En Prueba',
        'canceled' => 'Cancelada',
        'past_due' => 'Pago Vencido',
        'incomplete' => 'Incompleta',
    ],

    // BillingPeriod
    'billing_period' => [
        'monthly' => 'Mensual',
        'yearly' => 'Anual',
    ],

    // BillingEventType
    'billing_event_type' => [
        'subscription_started' => 'Suscripción Iniciada',
        'plan_changed' => 'Plan Cambiado',
        'plugin_activated' => 'Plugin Activado',
        'plugin_deactivated' => 'Plugin Desactivado',
        'subscription_cancelled' => 'Suscripción Cancelada',
    ],

    // CancellationReason
    'cancellation_reason' => [
        'price' => 'Muy caro',
        'missing_features' => 'Faltan funciones',
        'closed_business' => 'Negocio cerrado',
        'competitor' => 'Cambió a competidor',
        'other' => 'Otro',
    ],

    // LoyaltyTier
    'loyalty_tier' => [
        'bronze' => 'Bronce',
        'silver' => 'Plata',
        'gold' => 'Oro',
        'vip' => 'VIP',
    ],
];
