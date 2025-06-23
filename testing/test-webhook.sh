#!/bin/bash

echo "🧪 Testing Midtrans webhook endpoint..."

# Check if containers are running
if ! docker-compose ps | grep -q "Up"; then
    echo "❌ Containers are not running. Please run ./setup-testing.sh first."
    exit 1
fi

# Test webhook endpoint
echo "📡 Testing webhook endpoint..."
echo "URL: http://localhost:8000/api/midtrans/notification"
echo ""

# Test with sample data
echo "📤 Sending test webhook data..."
curl -X POST http://localhost:8000/api/midtrans/notification \
  -H "Content-Type: application/json" \
  -d '{
    "order_id": "TEST-ORDER-123",
    "transaction_status": "settlement",
    "fraud_status": "accept",
    "payment_type": "bank_transfer",
    "status_code": "200",
    "gross_amount": "100000",
    "signature_key": "test_signature_key"
  }' \
  -w "\n\nHTTP Status: %{http_code}\nResponse Time: %{time_total}s\n"

echo ""
echo "✅ Webhook test completed!"
echo ""
echo "📊 Check the response above to verify:"
echo "   - HTTP Status should be 200"
echo "   - Response should contain success message"
echo ""
echo "🔍 To view logs:"
echo "   docker-compose logs -f app"
echo ""
echo "🌐 To access phpMyAdmin:"
echo "   http://localhost:8080" 