#!/bin/bash
set -e

echo "========================================="
echo "Testing PHP FinanzOnline Webservices"
echo "across multiple PHP versions"
echo "========================================="
echo ""

for version in php74 php80 php81 php82 php83 php84; do
    echo "----------------------------------------"
    echo "Testing $version..."
    echo "----------------------------------------"
    podman-compose run --rm $version
    echo ""
done

echo "========================================="
echo "All tests passed! ✓"
echo "========================================="
