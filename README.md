# tz
Hello. Here is my tz. RandomQuoteBlock will have issues with caching for Anonymous user, but according to the testing task, it was not clear if it should work for anonymous the same way. Although, max_age = 0 is a bad option since all the page is not cached, it was added just as a simple way to prevent caching. In case it should, I would have reworked it the way, it is done with StatisticsBlock. I would load content via Ajax through controller to prevent caching for anonymous and not messing with page cache.
