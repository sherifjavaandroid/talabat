<div class="grid grid-cols-1 gap-6 mt-10 md:grid-cols-2 lg:grid-cols-3">

    {{-- Firebase settings --}}
    <x-settings-item title="{{ __('Push notification (Firebase)') }}" wireClick="$set('showNotification',true)">
        <x-heroicon-o-speakerphone class="w-5 h-5 {{ isRTL() ? 'ml-4' : 'mr-4' }}" />
    </x-settings-item>

    {{-- app settings --}}
    <x-settings-item title="{{ __('Web App Settings') }}" wireClick="$set('showApp',true)">
        <x-heroicon-o-cog class="w-5 h-5 {{ isRTL() ? 'ml-4' : 'mr-4' }}" />
    </x-settings-item>

    {{-- file size settings --}}
    <x-settings-item title="{{ __('File Upload Limit Settings') }}" wireClick="$set('showFileLimits',true)">
        <x-heroicon-o-upload class="w-5 h-5 {{ isRTL() ? 'ml-4' : 'mr-4' }}" />
    </x-settings-item>

    {{-- custom message --}}
    <x-settings-item title="{{ __('Custom Notification Messages') }}"
        wireClick="$set('showCustomNotificationMessage',true)">
        <x-heroicon-o-bell class="w-5 h-5 {{ isRTL() ? 'ml-4' : 'mr-4' }}" />
    </x-settings-item>


    {{-- Privacy policy --}}
    <x-settings-item title="{!! __('Privacy & Policy') !!}" wireClick="$set('showPrivacy',true)">
        <x-heroicon-o-eye-off class="w-5 h-5 {{ isRTL() ? 'ml-4' : 'mr-4' }}" />
    </x-settings-item>

    {{-- Contact info --}}
    <x-settings-item title="{{ __('Contact Info') }}" wireClick="$set('showContact',true)">
        <x-heroicon-o-chat class="w-5 h-5 {{ isRTL() ? 'ml-4' : 'mr-4' }}" />
    </x-settings-item>

    {{-- Terms and conditions --}}
    <x-settings-item title="{!! __('Terms & Conditions') !!}" wireClick="$set('showTerms',true)">
        <x-heroicon-o-clipboard-list class="w-5 h-5 {{ isRTL() ? 'ml-4' : 'mr-4' }}" />
    </x-settings-item>

    {{-- Terms and conditions --}}
    <x-settings-item title="{{ __('Page Settings') }}" wireClick="$set('showPageSetting',true)">
        <x-heroicon-o-book-open class="w-5 h-5 {{ isRTL() ? 'ml-4' : 'mr-4' }}" />
    </x-settings-item>

</div>
