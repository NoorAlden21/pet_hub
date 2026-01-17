<?php

return [

    /*
    |--------------------------------------------------------------------------
    | General CRUD messages
    |--------------------------------------------------------------------------
    */

    'created_successfully' => 'Created successfully.',
    'updated_successfully' => 'Updated successfully.',
    'deleted_successfully' => 'Deleted successfully.',

    /*
    |--------------------------------------------------------------------------
    | Pet Types
    |--------------------------------------------------------------------------
    */

    'pet_type' => [
        'created' => 'Pet type created successfully.',
        'updated' => 'Pet type updated successfully.',
        'deleted' => 'Pet type deleted successfully.',
        'not_found' => 'Pet type not found.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Pet Breeds
    |--------------------------------------------------------------------------
    */

    'pet_breed' => [
        'created' => 'Pet breed created successfully.',
        'updated' => 'Pet breed updated successfully.',
        'deleted' => 'Pet breed deleted successfully.',
        'not_found' => 'Pet breed not found.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Pets
    |--------------------------------------------------------------------------
    */

    'pet' => [
        'created' => 'Pet created successfully.',
        'updated' => 'Pet updated successfully.',
        'deleted' => 'Pet deleted successfully.',
        'not_found' => 'Pet not found.',
    ],

    /* 
    |--------------------------------------------------------------------------
    | Product Categories
    |--------------------------------------------------------------------------
    */

    'product_category' => [
        'created'   => 'Product category created successfully.',
        'updated'   => 'Product category updated successfully.',
        'deleted'   => 'Product category deleted successfully.',
        'not_found' => 'Product category not found.',
    ],

    /* 
    |--------------------------------------------------------------------------
    | Product
    |--------------------------------------------------------------------------
    */

    'product' => [
        'created' => 'Product created successfully.',
        'updated' => 'Product updated successfully.',
        'deleted' => 'Product deleted successfully.',
        'not_found' => 'Product not found.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Cart
    |--------------------------------------------------------------------------
    */

    'cart' => [
        'created'   => 'Cart created successfully.',
        'updated'   => 'Cart updated successfully.',
        'deleted'   => 'Cart deleted successfully.',
        'not_found' => 'Cart not found.',
        'cleared'   => 'Cart cleared successfully',
    ],

    /*
    |--------------------------------------------------------------------------
    | Cart Items
    |--------------------------------------------------------------------------
    */

    'cart_item' => [
        'created'   => 'Item added to cart successfully.',
        'updated'   => 'Cart item updated successfully.',
        'deleted'   => 'Cart item deleted successfully.',
        'not_found' => 'Cart item not found.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Orders
    |--------------------------------------------------------------------------
    */

    'order' => [
        'created'           => 'Order created successfully.',
        'updated'           => 'Order updated successfully.',
        'deleted'           => 'Order deleted successfully.',
        'not_found'         => 'Order not found.',
        'status_updated'    => 'Order status updated successfully.',
        'cancelled'         => 'Order cancelled successfully.',
        'cannot_cancel'     => 'Only pending orders can be cancelled.',
        'insufficient_stock' => 'The requested quantity is not available in stock.',
    ],

    /* 
    |--------------------------------------------------------------------------
    | Boarding Service
    |--------------------------------------------------------------------------
    */

    'boarding_service' => [
        'created'   => 'Boarding service created successfully.',
        'updated'   => 'Boarding service updated successfully.',
        'deleted'   => 'Boarding service deleted successfully.',
        'not_found' => 'Boarding service not found.',
        'invalid_services' => 'One or more selected services are invalid or inactive.',
    ],

    /* 
    |--------------------------------------------------------------------------
    | Boarding Reservations
    |--------------------------------------------------------------------------
    */

    'boarding_reservations' => [
        'cannot_cancel' => 'This reservation cannot be cancelled in its current status.',
        'cannot_change_status' => 'Cannot change status of a cancelled or completed reservation.',
    ],

    'appointment_category' => [
        'created'   => 'Appointment category created successfully.',
        'updated'   => 'Appointment category updated successfully.',
        'deleted'   => 'Appointment category deleted successfully.',
        'not_found' => 'Appointment category not found.',
    ],

    'appointment' => [
        'created' => 'Appointment created successfully.',
        'status_updated' => 'Appointment status updated successfully.',
        'cancelled' => 'Appointment cancelled successfully.',
        'cannot_cancel' => 'This appointment cannot be cancelled in its current status.',
        'cannot_change_status' => 'This appointment status cannot be changed in its current status.',
        'rejection_reason_required' => 'Rejection reason is required.',
        'not_found' => 'Appointment not found.',
    ],

    /* 
    |--------------------------------------------------------------------------
    | Adoption Applications
    |--------------------------------------------------------------------------
    */

    'adoption_application' => [
        'cannot_cancel' => 'You can’t cancel the adoption application in its current status.',
    ],

];
