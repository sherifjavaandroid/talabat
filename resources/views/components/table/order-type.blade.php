<p class="flex text-xs font-light">
@switch($model->order_type)
@case('parcel')
<x-feathericon-package class="w-4 h-4" />
@break
@case('service')
<x-heroicon-o-briefcase class="w-4 h-4" />
@break
@case('food')
<x-fluentui-food-16-o class="w-4 h-4" />
@break
@case('pharmacy')
<x-tabler-building-hospital class="w-4 h-4" />
@break
@case('grocery')
<x-heroicon-o-shopping-cart class="w-4 h-4" />
@break
@case('taxi')
<x-fluentui-vehicle-car-28-o class="w-4 h-4" />
@break
@default

@endswitch

{{-- <span class="mx-1 text-xs">{{  ucfirst($model->order_type) }}</span> --}}
</p>
