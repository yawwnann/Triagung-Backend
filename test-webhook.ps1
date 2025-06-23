Write-Host "Testing Midtrans Webhook..." -ForegroundColor Green

$body = @{
    order_id = "TEST-001"
    transaction_status = "settlement"
    fraud_status = "accept"
    payment_type = "bank_transfer"
    status_code = "200"
    gross_amount = "100000"
    signature_key = "test_signature"
} | ConvertTo-Json

try {
    $response = Invoke-WebRequest -Uri "http://localhost:8000/api/midtrans/notification" -Method POST -Headers @{"Content-Type"="application/json"} -Body $body
    Write-Host "Response Status: $($response.StatusCode)" -ForegroundColor Green
    Write-Host "Response Body: $($response.Content)" -ForegroundColor Yellow
} catch {
    Write-Host "Error: $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host "`nWebhook test completed!" -ForegroundColor Green 