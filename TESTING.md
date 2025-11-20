# API Testing

## Test Suite Location

The comprehensive VipeCloud API v4.0 test suite is located in the **vc3 repository**:

```
../vc3/tests/
```

## Quick Links

- **Full Documentation**: [`../vc3/tests/README.md`](../vc3/tests/README.md)
- **Quick Start Guide**: [`../vc3/tests/QUICKSTART.md`](../vc3/tests/QUICKSTART.md)
- **Test Coverage**: [`../vc3/tests/TEST-COVERAGE.md`](../vc3/tests/TEST-COVERAGE.md)

## Why the Test Suite is in vc3

The test suite is located alongside the API implementation code in the `vc3` repository because:

1. **Direct Access to Implementation** - Tests can access the actual VipeCloudAPI v4.0 PHP classes
2. **Single Source of Truth** - Tests live with the code they test
3. **Easier Development** - Developers working on API implementation have immediate access to tests
4. **CI/CD Integration** - Tests run as part of the main application's build pipeline

## Running Tests

From the VipeCloud root directory:

```bash
# Navigate to the test suite
cd ../vc3/tests

# Configure credentials (first time only)
cp config.example.php config.php
# Edit config.php with your test account credentials

# Run all tests
./run-tests.sh

# Run specific test suite
./run-tests.sh contacts
./run-tests.sh emails
```

## Test Suite Features

- ✅ **70+ comprehensive tests** covering all v4.0 endpoints
- ✅ **Deterministic & replicable** - same results every time
- ✅ **Automatic cleanup** - no orphaned test data
- ✅ **Full CRUD coverage** - GET, POST, PUT, PATCH, DELETE
- ✅ **Rate limit aware** - respects API throttling
- ✅ **200+ assertions** - thorough validation

## Documentation Structure

This repository (`vipecloud_api`) contains:
- API endpoint documentation (markdown files)
- API usage examples
- Integration guides

The `vc3` repository contains:
- API implementation code
- API test suite (`vc3/tests/`)
- Application code

## Need Help?

- **Test Suite Documentation**: See `../vc3/tests/README.md`
- **API Issues**: support@vipecloud.com
- **Test Suite Issues**: Check the test suite README for troubleshooting
