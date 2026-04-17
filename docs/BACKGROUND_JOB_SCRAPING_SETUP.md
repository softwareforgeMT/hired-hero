# Background Job Scraping Setup Guide

## Overview
This system moves job scraping from a synchronous blocking operation to a background queue job. Users no longer wait on the setup page while jobs are being scraped. Instead, they see a progress modal and can take the job matches preview when ready.

## Components Created

### 1. **ScrapeJobs Queue Job** (`app/Jobs/ScrapeJobs.php`)
- Handles the actual job scraping in the background
- Uses the same scraping logic as the ScraperController
- Updates progress in real-time via the `ScrapingProgress` model
- Implements timeout and error handling

### 2. **ScrapingProgress Model** (`app/Models/ScrapingProgress.php`)
- Tracks the status and progress of job scraping
- Stores progress percentage (0-100), status, and messages
- Enables real-time progress polling from the frontend

### 3. **Database Migration** (`database/migrations/2026_04_10_000000_create_scraping_progress_table.php`)
- Creates the `scraping_progress` table
- Stores user_id, status, progress, message, total_jobs, and timestamps

### 4. **JobScrapingController** (`app/Http/Controllers/Api/JobScrapingController.php`)
- API endpoints for starting scraping and checking progress
- Routes:
  - `POST /api/scraping/start` - Start background job
  - `GET /api/scraping/progress` - Get current progress
  - `GET /api/scraping/is-complete` - Check if jobs are scraped
  - `GET /api/scraping/queue-status` - Check if queue is running
  - `GET /api/scraping/job-matches` - Get scraped job matches

### 5. **Progress Modal Component** (`resources/views/components/scraping-progress-modal.blade.php`)
- Bottom-right corner modal showing scraping progress
- Real-time progress bar with percentage
- Job count display
- Error handling with user-friendly messages

### 6. **Updated Step-8 Blade** (`resources/views/placement/wizard/step-8.blade.php`)
- Modified form submission to trigger background job
- Shows progress modal on completion
- Provides "Preview Matches" and "Dashboard" buttons
- Real-time polling for progress updates

## Required Configuration

### 1. Queue Configuration (.env)
```env
# Set up a queue driver (default: sync, use 'database' or 'redis' for production)
QUEUE_CONNECTION=database
# OR for Redis:
# QUEUE_CONNECTION=redis
```

### 2. Database Setup
Run migrations:
```bash
php artisan migrate
```

### 3. Queue Worker
For production, you need to run the queue worker:

```bash
# Run the queue worker (listens for jobs)
php artisan queue:work

# OR with specific settings:
php artisan queue:work --timeout=3600 --tries=1 --delay=0
```

For local development with `sync` driver, jobs run immediately without needing a worker.

### 4. (Optional) Horizon Monitoring (For Redis)
For better queue monitoring with Redis:
```bash
php artisan horizon:install
php artisan migrate
php artisan horizon
```

## How It Works

### User Flow
1. User completes Step 7 (Resume submission)
2. User arrives at Step 8 (Select Target Roles)
3. User selects one or more roles and clicks "Complete Setup"
4. Progress modal appears showing real-time scraping progress
5. System scrapes jobs from multiple platforms:
   - Indeed
   - LinkedIn
   - Wellfound
   - WorkDay
6. Progress updates every 2 seconds
7. When complete, user sees "Jobs Found!" and can:
   - Click "Preview Matches" to see job listings
   - Click "Go to Dashboard" to return home

### Backend Process
1. API receives `/api/scraping/start` request
2. Creates ScrapingProgress record with status="pending"
3. Dispatches ScrapeJobs to queue
4. Returns immediately to user
5. Frontend starts polling `/api/scraping/progress`
6. ScrapeJobs processes:
   - Initializes progress (5%)
   - For each role: Scrapes Indeed (10%), LinkedIn (15%), Wellfound (25%), WorkDay (35%)
   - Fetches job descriptions (50-80%)
   - Saves to JobMatch table (80-100%)
7. Updates ScrapingProgress with final status="completed"
8. Frontend detects completion and shows success modal

## Testing

### Local Development (with sync driver)
```bash
# Jobs run immediately without needing a worker
# Just ensure QUEUE_CONNECTION=sync in .env
php artisan serve
```

### With Database Queue
```bash
# Terminal 1: Start the queue worker
php artisan queue:work

# Terminal 2: Run the dev server
php artisan serve
```

### Test API Endpoints
```bash
# Start scraping
curl -X POST http://localhost:8000/api/scraping/start \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -d '{"selected_roles": ["Software Engineer"]}'

# Check progress
curl http://localhost:8000/api/scraping/progress \
  -H "Authorization: Bearer YOUR_TOKEN"

# Check queue status
curl http://localhost:8000/api/scraping/queue-status \
  -H "Authorization: Bearer YOUR_TOKEN"
```

## Troubleshooting

### Jobs Not Processing
**Issue:** Jobs sit in pending status indefinitely
**Solution:** 
1. Check if queue worker is running: `php artisan queue:work`
2. Verify QUEUE_CONNECTION in .env
3. Check database (if using database driver): `php artisan queue:failed`
4. Check logs in `storage/logs/`

### Timeout Errors
**Issue:** Scraping times out before completion
**Solution:**
1. Increase timeout in ScrapeJobs: `public $timeout = 3600;`
2. Check network connectivity to scraping targets
3. Reduce number of job details to fetch

### Progress Not Updating
**Issue:** Frontend doesn't see progress updates
**Solution:**
1. Check browser console for errors
2. Verify API routes are accessible: `php artisan route:list | grep scraping`
3. Check database for ScrapingProgress records
4. Enable debug mode: `APP_DEBUG=true`

### High Database Load
**Issue:** Too many polling requests
**Solution:**
1. Increase polling interval in step-8.js from 2000ms to higher
2. Use Redis queue instead of database for better performance
3. Reduce progress update frequency in ScrapeJobs

## Performance Optimization

### For Production
1. **Use Redis Queue:**
   ```env
   QUEUE_CONNECTION=redis
   ```

2. **Run Multiple Workers:**
   ```bash
   php artisan queue:work --num-processes=4
   ```

3. **Use Supervisor for Auto-Restart:**
   Create `/etc/supervisor/conf.d/laravel-queue.conf`

4. **Monitor with Horizon:**
   ```bash
   php artisan horizon
   ```

## Architecture Benefits

✅ **Non-blocking:** Users see immediate feedback without waiting
✅ **Scalable:** Can handle multiple users scraping simultaneously  
✅ **Recoverable:** Failed jobs can be retried
✅ **Monitorable:** Real-time progress tracking
✅ **Reliable:** Timeout protection and error handling
✅ **User-Friendly:** Clear visual feedback throughout the process

## Future Enhancements

- Add email notification when scraping completes
- Implement incremental job updates (show jobs as they're found)
- Add cron job to automatically refresh job listings daily
- Store scraping history for analytics
- Add rate limiting to prevent abuse
- Implement smart caching for frequently searched roles
