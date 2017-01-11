Conversoin Path Tracking
============================

## Setup & Requirements

1. Zookeeper
2. Kafka
3. Redis
4. Cassandra
5. Mysql/Postgres
6. ClickHouse
7. ElasticSearch


## Tracking

1. Impressions
2. Clicks
3. Leads
4. Click Out
5. Sale

```html
impression
http://localhost:3000/tracker?campaign_id=1&website_id=1&type=0
click
http://localhost:3000/tracker?cid=1&mid=1&wid=1&t=1
lead
http://localhost:3000/tracker?cid=1&mid=1&wid=1&t=2
click out
http://localhost:3000/tracker?cid=1&mid=1&wid=1&t=3
sale
http://localhost:3000/tracker?cid=1&mid=1&wid=1&t=4
```