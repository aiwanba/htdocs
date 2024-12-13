<?php
require_once 'lib/trade/stock.php';

// 获取市场数据
$stock_manager = new StockManager();
$hot_stocks = $stock_manager->getHotStocks(10);
$latest_ipos = $stock_manager->getLatestIPOs(5);
$latest_trades = $stock_manager->getLatestTrades(10);
$market_stats = $stock_manager->getMarketStats();

// 设置页面标题
$page_title = '首页';

include 'templates/common/home.php'; 