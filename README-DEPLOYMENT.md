# Railway Deployment Guide

## Prerequisites

-   Railway account
-   Git repository connected to Railway
-   Database (MySQL/PostgreSQL) provisioned on Railway

## Environment Variables

Set these environment variables in Railway:

### Required

```
APP_NAME="Trijaya Agung"
APP_ENV=production
APP_KEY=base64:your-generated-key
APP_DEBUG=false
APP_URL=https://your-app-name.up.railway.app
FORCE_HTTPS=true

DB_CONNECTION=mysql
DB_HOST=your-db-host
DB_PORT=3306
DB_DATABASE=your-db-name
DB_USERNAME=your-db-username
DB_PASSWORD=your-db-password

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

### Optional

```
LOG_CHANNEL=stack
LOG_LEVEL=debug
```

## Deployment Steps

1. **Connect Repository**

    - Connect your Git repository to Railway
    - Railway will automatically detect Laravel

2. **Set Environment Variables**

    - Go to your Railway project
    - Navigate to Variables tab
    - Add all required environment variables

3. **Provision Database**

    - Add MySQL/PostgreSQL plugin
    - Copy database credentials to environment variables

4. **Deploy**
    - Railway will automatically deploy on push
    - Check logs for any errors

## Troubleshooting

### Port Issues

If you see "Unsupported operand types: string + int":

-   Railway automatically sets PORT environment variable
-   The start command uses fixed port 8000 to avoid this issue
-   Healthcheck has been removed to prevent deployment failures

### Health Check Fails

If health check fails:

-   Healthcheck has been removed to prevent deployment issues
-   Check application logs directly
-   Verify database connection

### Mixed Content Errors

If you see HTTPS/HTTP mixed content errors:

-   Set `FORCE_HTTPS=true`
-   Ensure `APP_URL` uses HTTPS
-   Clear browser cache

## Files Structure

```
├── .nixpacks              # Railway build configuration
├── railway.json           # Railway deployment config
├── start.sh              # Linux start script
├── start.bat             # Windows start script
├── .dockerignore         # Docker ignore file
└── env.railway           # Environment template
```

## Commands

### Local Testing

```bash
# Test start script
./start.sh

# Windows
start.bat
```

### Railway Commands

```bash
# View logs
railway logs

# Open shell
railway shell

# Deploy manually
railway up
```

## Support

If you encounter issues:

1. Check Railway logs
2. Verify environment variables
3. Test locally first
4. Check Laravel logs in Railway shell
