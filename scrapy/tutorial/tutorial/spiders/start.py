import scrapy
from tutorial.items import TutorialItem


class QuotesSpider(scrapy.Spider):
    name = "quotes"

    start_urls = [
        'http://vipthz.com/forum-220-1.html',
    ]

    def parse(self, response):
        urls = response.xpath('//*[@id="threadlisttableid"]//a[@class="s xst"]/@href').extract()
        next_url = response.xpath('//a[@class="nxt"]/@href').extract_first()
        #urls = response.xpath('/html/body/div[7]/div[4]/div/div/div[4]/div[2]/form/table/tbody[40]/tr/th/a[2]/@href').extract()
        for url in urls:
            url = response.urljoin(url)
            #print('url',url)
            #pass
            yield scrapy.Request(url, callback=self.imageparse)
        if next_url is not None:
            next_url = response.urljoin(next_url)
            yield scrapy.Request(url=next_url, callback=self.parse)

    def imageparse(self, response):
        item = TutorialItem()
        
        #test_urls = response.xpath('//*[@id="postlist"]//img/@file').extract()
        #test_title = response.xpath('//*[@id="thread_subject"]/text()').extract()
        #print('title : ',test_title)
        #print('test_urls:',test_urls)
        #test_file_urls = response.xpath('//p[@class="attnm"]/a/@href').extract()
        #item['file_urls'] = test_urls
        #item['name'] = test_title
        #for url in test_file_urls:
        #    url = response.urljoin(url)
        #    yield scrapy.Request(url, meta={'item':item}, callback=self.fileparse)

        
        item['file_urls'] = response.xpath('//*[@id="postlist"]//img/@file').extract()
        item['name'] = response.xpath('//*[@id="thread_subject"]/text()').extract()
        file_urls = response.xpath('//p[@class="attnm"]/a/@href').extract()
        for url in file_urls:
            url = response.urljoin(url)
            yield scrapy.Request(url, meta={'item':item}, callback=self.fileparse)
        #print('image_urls',item['image_urls'])#提取图片链接
        #yield item
        
        pass

    def fileparse(self, response):
        item = response.meta['item']
        test_file_urls = response.xpath('/html/body//div/a[@onclick="hideWindow(\'imc_attachad\')"]/@href').extract()
        print('file_url : ',test_file_urls)
        item['file_urls'] += test_file_urls

        yield item