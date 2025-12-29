<?php

namespace App\Observers;

use Spatie\Activitylog\Models\Activity;

class ProductObserver
{
    public function created($product)
    {
        $activity = new Activity();
        $activity->log_name = 'product-management';
        $activity->description = "New product created: {$product->name}";
        $activity->subject_type = get_class($product);
        $activity->subject_id = $product->id;
        if (auth()->check()) {
            $activity->causer_type = get_class(auth()->user());
            $activity->causer_id = auth()->id();
        }
        $activity->properties = json_encode([
            'product_name' => $product->name,
            'sku' => $product->sku,
            'category' => $product->category,
        ]);
        $activity->save();
    }

    public function updated($product)
    {
        $activity = new Activity();
        $activity->log_name = 'product-management';
        $activity->description = "Product updated: {$product->name}";
        $activity->subject_type = get_class($product);
        $activity->subject_id = $product->id;
        if (auth()->check()) {
            $activity->causer_type = get_class(auth()->user());
            $activity->causer_id = auth()->id();
        }
        $activity->properties = json_encode([
            'product_name' => $product->name,
            'sku' => $product->sku,
            'changes' => $product->getChanges(),
        ]);
        $activity->save();
    }

    public function deleted($product)
    {
        $activity = new Activity();
        $activity->log_name = 'product-management';
        $activity->description = "Product deleted: {$product->name}";
        $activity->subject_type = get_class($product);
        $activity->subject_id = $product->id;
        if (auth()->check()) {
            $activity->causer_type = get_class(auth()->user());
            $activity->causer_id = auth()->id();
        }
        $activity->properties = json_encode([
            'product_name' => $product->name,
            'sku' => $product->sku,
        ]);
        $activity->save();
    }
}
