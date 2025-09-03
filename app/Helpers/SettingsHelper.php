<?php

if (!function_exists('setting')) {
    /**
     * Get a setting value by key
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function setting(string $key, $default = null)
    {
        return \App\Models\Setting::get($key, $default);
    }
}

if (!function_exists('setting_asset')) {
    /**
     * Get a setting value as an asset URL
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function setting_asset(string $key, $default = null)
    {
        $value = \App\Models\Setting::get($key, $default);
        if ($value) {
            // For favicons and other settings images, check multiple possible locations
            if (str_starts_with($key, 'site_')) {
                // Check if file exists in the public uploads directory first
                if (file_exists(public_path('uploads/' . $value))) {
                    // Use asset URL for direct file access
                    return asset('uploads/' . $value);
                }
                
                // Also check in the storage public directory
                if (file_exists(storage_path('app/public/' . $value))) {
                    try {
                        return \Illuminate\Support\Facades\Storage::disk('public')->url($value);
                    } catch (\Exception $e) {
                        // Continue to fallback
                    }
                }
            }
            
            // Check if it's already a full URL
            if (filter_var($value, FILTER_VALIDATE_URL)) {
                return $value;
            }
            
            // Check if file exists in the public uploads directory first
            if (file_exists(public_path('uploads/' . $value))) {
                // Use asset URL for direct file access
                return asset('uploads/' . $value);
            }
            
            // Fallback to Storage URL
            try {
                $storageUrl = \Illuminate\Support\Facades\Storage::disk('public')->url($value);
                if (filter_var($storageUrl, FILTER_VALIDATE_URL)) {
                    return $storageUrl;
                }
            } catch (\Exception $e) {
                // Storage URL generation failed, continue to fallback
            }
            
            // Final fallback - construct URL manually
            return url('uploads/' . $value);
        }
        return $default;
    }
}

if (!function_exists('settings_group')) {
    /**
     * Get all settings for a group
     *
     * @param string $group
     * @return array
     */
    function settings_group(string $group)
    {
        return \App\Models\Setting::getGroup($group);
    }
}