#!/bin/bash

echo "Testing Midtrans Webhook..."

# Test data untuk webhook
curl -X POST http://localhost:8000/api/midtrans/notification \
  -H "Content-Type: application/json" \
  -d '{
    "order_id": "TEST-001",
    "transaction_status": "settlement",
    "fraud_status": "accept",
    "payment_type": "bank_transfer",
    "status_code": "200",
    "gross_amount": "100000",
    "signature_key": "test_signature"
  }'

echo -e "\n\nWebhook test completed!" 