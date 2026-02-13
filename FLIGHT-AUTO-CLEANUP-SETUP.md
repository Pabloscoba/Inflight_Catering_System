# Flight Auto-Cleanup System Setup Guide

## 📋 Overview
System hii inafanya automatic cleanup na status updates kwa flights zilizopita. Inabadilisha flights old automatically na kuzi-hide zisiwepo kwenye default listings.

## ✨ Features
1. **Auto Status Updates**: Flights zimepita zina-update automatic kutoka "scheduled" → "departed" → "arrived"
2. **Auto Archive**: Flights za zamani (30+ days) zinakuwa "completed" na hazionekani default
3. **Scheduled Tasks**: Command hii inaenda automatically kila saa bila manual intervention

## 🚀 How It Works

### 1. Flight Statuses
```
scheduled   → Flight haijatoka bado (future)
boarding    → Inajihazisha (manual set)
departed    → Imetoka (auto-set when departure time passed)
arrived     → Imefika (auto-set when arrival time passed)
cancelled   → Imefutwa (manual set)
completed   → Archived/Old flight (auto-set after 30 days)
```

### 2. Automatic Updates (Hourly)
- **Scheduled → Departed**: Flights zenye departure_time < now()
- **Departed → Arrived**: Flights zenye arrival_time < now()
- **Departed/Arrived → Completed**: Flights older than 30 days

### 3. Filtering
- **Active Flights**: Hazionyeshi "completed" flights kwenye default listing
- **Request Creation**: Hazionyeshi completed/expired flights
- **Archived View**: Unaweza ku-filter by status="completed" kuona old flights

## 🔧 Manual Commands

### Run status update manually:
```bash
php artisan flights:update-statuses
```

### Check scheduled tasks:
```bash
php artisan schedule:list
```

### Test scheduler (for development):
```bash
php artisan schedule:work
```

## 🖥️ Production Setup

### For Linux/Ubuntu (Cron Job):
Add this to your crontab:
```bash
crontab -e
```

Add this line:
```
* * * * * cd /path/to/your/project && php artisan schedule:run >> /dev/null 2>&1
```

### For Windows (Task Scheduler):
1. Open **Task Scheduler**
2. Create Basic Task
3. Trigger: Daily
4. Action: Start a program
5. Program: `php.exe`
6. Arguments: `C:\path\to\your\project\artisan schedule:run`
7. Start in: `C:\path\to\your\project`
8. Set to run every hour

### For Shared Hosting:
Contact your hosting provider to setup cron job:
```
* * * * * /usr/bin/php /home/username/public_html/artisan schedule:run
```

## 📊 Database Changes
New status "completed" added to flights table:
```sql
ALTER TABLE flights 
MODIFY COLUMN status ENUM('scheduled', 'boarding', 'departed', 'arrived', 'cancelled', 'completed') 
DEFAULT 'scheduled';
```

## 🔍 Model Scopes Available

### Active Flights (default):
```php
Flight::active()->get();
```

### Upcoming Only:
```php
Flight::upcoming()->get();
```

### Expired/Old:
```php
Flight::expired()->get();
```

### By Status:
```php
Flight::scheduled()->get();
Flight::departed()->get();
Flight::completed()->get();
```

## 📝 Usage in Code

### Controller Example:
```php
// Show only active flights (not completed)
$flights = Flight::where('status', '!=', 'completed')
    ->orderBy('departure_time', 'desc')
    ->paginate(20);
```

### Blade Example:
```php
@foreach($flights->active() as $flight)
    {{ $flight->flight_number }}
@endforeach
```

## ⚠️ Important Notes

1. **Scheduler Must Run**: Task Scheduler au Cron Job lazima iwe active ili auto-updates zifanye kazi
2. **Development**: Use `php artisan schedule:work` kwa testing
3. **Production**: Setup proper cron job
4. **Manual Override**: Unaweza bado ku-set status manual ikiwa unahitaji
5. **Old Data**: Flights "completed" zinabaki database lakini hazionyeshwi default

## 🧪 Testing

Test command manually:
```bash
# Update statuses
php artisan flights:update-statuses

# Check if scheduler is setup correctly
php artisan schedule:list

# Run scheduler (for development only)
php artisan schedule:work
```

## 📞 Support
Kama kuna issues, check:
1. Cron job/Task Scheduler iko running?
2. Database ina status "completed"?
3. Laravel logs: `storage/logs/laravel.log`
4. Run manual command: `php artisan flights:update-statuses`

---

**Last Updated**: January 27, 2026
**Version**: 1.0
