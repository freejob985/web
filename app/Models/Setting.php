<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'label',
        'description',
        'options',
        'is_public'
    ];

    protected $casts = [
        'options' => 'array'
    ];

    // Scopes
    public function scopeByGroup($query, $group)
    {
        return $query->where('group', $group);
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    // Static methods for easy access
    public static function get($key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->getValue() : $default;
    }

    public static function set($key, $value, $type = 'string', $group = 'general')
    {
        $setting = static::where('key', $key)->first();
        
        if ($setting) {
            $setting->update(['value' => $value]);
        } else {
            $setting = static::create([
                'key' => $key,
                'value' => $value,
                'type' => $type,
                'group' => $group,
                'label' => ucfirst(str_replace('_', ' ', $key))
            ]);
        }
        
        return $setting;
    }

    public static function getGroup($group)
    {
        return static::byGroup($group)->get()->mapWithKeys(function ($setting) {
            return [$setting->key => $setting->getValue()];
        });
    }

    public static function getPublic()
    {
        return static::public()->get()->mapWithKeys(function ($setting) {
            return [$setting->key => $setting->getValue()];
        });
    }

    // Instance methods
    public function getValue()
    {
        switch ($this->type) {
            case 'boolean':
                return (bool) $this->value;
            case 'number':
                return is_numeric($this->value) ? (float) $this->value : 0;
            case 'json':
                return json_decode($this->value, true);
            default:
                return $this->value;
        }
    }

    public function setValue($value)
    {
        switch ($this->type) {
            case 'boolean':
                $this->value = $value ? '1' : '0';
                break;
            case 'number':
                $this->value = (string) $value;
                break;
            case 'json':
                $this->value = json_encode($value);
                break;
            default:
                $this->value = (string) $value;
        }
        
        $this->save();
    }

    // Helper method to append image URLs
    public function appendImageUrls()
    {
        if ($this->type === 'file' && $this->value) {
            $this->image_url = asset('storage/' . $this->value);
        }
        return $this;
    }
}