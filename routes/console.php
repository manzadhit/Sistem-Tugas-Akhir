<?php

use Illuminate\Support\Facades\Schedule;


Schedule::command('bimbingan:reminders')->dailyAt('08:00')->timezone('Asia/Makassar');
