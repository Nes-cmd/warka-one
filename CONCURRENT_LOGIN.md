# Concurrent Login Control

This feature allows you to control whether users can have multiple concurrent sessions (login from multiple devices/browsers simultaneously).

## Configuration

### Environment Variable

Add the following to your `.env` file:

```env
# Concurrent Login Control
ALLOW_CONCURRENT_LOGIN=true
```

- `true` (default): Users can login from multiple devices/browsers
- `false`: Users can only have one active session. Logging in from a new device will logout from all other devices.

### Session Configuration

The feature requires database-based sessions. The session driver is automatically set to `database` in `config/session.php`.

## How It Works

### When `ALLOW_CONCURRENT_LOGIN=false`:

1. **During Login**: When a user logs in, all their existing sessions are terminated
2. **During Request**: The middleware checks and terminates any other sessions for the user
3. **Result**: Only the current session remains active

### When `ALLOW_CONCURRENT_LOGIN=true`:

- Users can login from multiple devices/browsers simultaneously
- No session termination occurs

## Management Commands

Use the artisan command to manage concurrent login settings:

### Check Status
```bash
php artisan auth:concurrent-login status
```

### Enable Concurrent Login
```bash
php artisan auth:concurrent-login enable
```

### Disable Concurrent Login
```bash
php artisan auth:concurrent-login disable
```

### View Sessions
```bash
# View all recent sessions
php artisan auth:concurrent-login sessions

# View sessions for specific user
php artisan auth:concurrent-login sessions --user=USER_ID
```

### Logout Sessions
```bash
# Logout specific user from all sessions
php artisan auth:concurrent-login logout --user=USER_ID

# Logout ALL users from ALL sessions
php artisan auth:concurrent-login logout --all
```

## Technical Implementation

### Components

1. **SessionManager Service** (`app/Services/SessionManager.php`)
   - Handles session management logic
   - Provides methods for terminating sessions
   - Checks concurrent login settings

2. **HandleConcurrentLogin Middleware** (`app/Http/Middleware/HandleConcurrentLogin.php`)
   - Runs on every request for authenticated users
   - Terminates other sessions when concurrent login is disabled

3. **AuthenticatedSessionController** (Modified)
   - Terminates existing sessions during login when concurrent login is disabled

4. **ManageConcurrentLogin Command** (`app/Console/Commands/ManageConcurrentLogin.php`)
   - Artisan command for managing settings and sessions

### Database

The feature uses the `sessions` table with the following structure:
- `id`: Session identifier
- `user_id`: Foreign key to users table
- `ip_address`: Client IP address
- `user_agent`: Client user agent
- `payload`: Session data
- `last_activity`: Last activity timestamp

## Security Considerations

- When concurrent login is disabled, users are immediately logged out from other devices
- Session termination is immediate and affects active users
- Consider notifying users about this behavior
- Monitor session activity for suspicious patterns

## Usage Examples

### Disable concurrent login for security
```bash
# Set in .env
ALLOW_CONCURRENT_LOGIN=false

# Or use command
php artisan auth:concurrent-login disable
```

### Monitor user sessions
```bash
# Check overall status
php artisan auth:concurrent-login status

# View sessions for user ID 123
php artisan auth:concurrent-login sessions --user=123
```

### Emergency logout
```bash
# Logout specific user
php artisan auth:concurrent-login logout --user=123

# Logout everyone (emergency)
php artisan auth:concurrent-login logout --all
```

## Testing

To test the feature:

1. Set `ALLOW_CONCURRENT_LOGIN=false` in `.env`
2. Login from one browser/device
3. Login from another browser/device with the same user
4. Verify the first session is terminated
5. Check that only one session exists in the database

## Troubleshooting

### Sessions not being terminated
- Ensure `SESSION_DRIVER=database` in `.env`
- Run `php artisan migrate` to create sessions table
- Clear application cache: `php artisan config:clear`

### Environment changes not taking effect
- Restart your web server/application
- Clear configuration cache: `php artisan config:clear`
- Verify `.env` file syntax