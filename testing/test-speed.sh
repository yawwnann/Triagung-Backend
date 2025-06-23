#!/bin/bash

echo "🚀 Docker Speed Test untuk Laravel Backend"
echo "=========================================="

# Test 1: Response Time API
echo ""
echo "📊 Test 1: Response Time API"
echo "----------------------------"

# Test multiple requests and calculate average
total_time=0
requests=10

for i in {1..$requests}; do
    start_time=$(date +%s%N)
    response=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:8000/api)
    end_time=$(date +%s%N)
    
    duration=$(( (end_time - start_time) / 1000000 ))  # Convert to milliseconds
    total_time=$((total_time + duration))
    
    echo "Request $i: ${duration}ms (Status: $response)"
done

average_time=$((total_time / requests))
echo "📈 Average Response Time: ${average_time}ms"

# Test 2: Database Connection Speed
echo ""
echo "🗄️ Test 2: Database Connection Speed"
echo "-----------------------------------"

start_time=$(date +%s%N)
docker-compose exec -T app php artisan tinker --execute="echo 'DB connected';" > /dev/null 2>&1
end_time=$(date +%s%N)
db_time=$(( (end_time - start_time) / 1000000 ))
echo "📊 Database Connection Time: ${db_time}ms"

# Test 3: Container Resource Usage
echo ""
echo "💻 Test 3: Container Resource Usage"
echo "----------------------------------"

echo "📊 Memory Usage:"
docker stats --no-stream --format "table {{.Container}}\t{{.CPUPerc}}\t{{.MemUsage}}\t{{.MemPerc}}"

echo ""
echo "📊 Container Status:"
docker-compose ps

# Test 4: Load Test (Simple)
echo ""
echo "🔥 Test 4: Simple Load Test"
echo "---------------------------"

echo "Testing 50 concurrent requests..."
start_time=$(date +%s%N)

# Run 50 requests in background
for i in {1..50}; do
    curl -s -o /dev/null http://localhost:8000/api &
done

# Wait for all requests to complete
wait

end_time=$(date +%s%N)
load_time=$(( (end_time - start_time) / 1000000 ))
echo "📈 Load Test Time (50 requests): ${load_time}ms"

# Test 5: Disk I/O Speed
echo ""
echo "💾 Test 5: Disk I/O Speed"
echo "------------------------"

echo "📊 Storage Performance:"
docker-compose exec app dd if=/dev/zero of=/tmp/test_file bs=1M count=10 2>/dev/null
docker-compose exec app dd if=/tmp/test_file of=/dev/null bs=1M 2>/dev/null
docker-compose exec app rm -f /tmp/test_file

# Test 6: Network Latency
echo ""
echo "🌐 Test 6: Network Latency"
echo "-------------------------"

echo "📊 Local Network Latency:"
ping -c 3 localhost | grep "time=" | awk '{print $7}' | sed 's/time=//'

echo ""
echo "✅ Speed Test Selesai!"
echo "====================="
echo "📋 Summary:"
echo "- Average API Response: ${average_time}ms"
echo "- Database Connection: ${db_time}ms"
echo "- Load Test (50 req): ${load_time}ms"
echo ""
echo "💡 Tips untuk Optimasi:"
echo "- Gunakan Redis untuk caching"
echo "- Optimize database queries"
echo "- Enable OPcache di PHP"
echo "- Gunakan CDN untuk static files" 