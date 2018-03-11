# -*- coding: utf-8 -*-

# Define your item pipelines here
#
# Don't forget to add your pipeline to the ITEM_PIPELINES setting
# See: https://doc.scrapy.org/en/latest/topics/item-pipeline.html
import os
import urllib
import scrapy
from scrapy.exceptions import DropItem
from scrapy.pipelines.images import ImagesPipeline
from scrapy.pipelines.files import FilesPipeline


class TutorialPipeline(ImagesPipeline):
    def file_path(self, request, response=None, info=None):
        #image_name = request.meta['item']['name'][0].split(' ')[1]
        image_name = request.meta['item']['name'][0]
        if not image_name:
            raise DropItem("fuck name")
        image_guid = request.url.split('/')[-1]
        return '%s/%s' % (image_name,image_guid)
    def get_media_requests(self, item, info):
        for image_url in item['image_urls']:
            yield scrapy.Request(url=image_url,meta={'item': item})

    def item_completed(self, results, item, info):
        image_paths = [x['path'] for ok, x in results if ok]
        #for ok,x in results:
         #   if ok:
          #      image_paths = [x['path']]
        #print('image_paths,,,,,,,',image_paths)
        if not image_paths:
            raise DropItem("Item contains no images")
        #item['image_paths'] = image_paths
        return item

class TutorialFilePipeline(FilesPipeline):
    def get_media_requests(self, item, info):
        for file_url in item['file_urls']:
            print('fuck this')
            yield scrapy.Request(url=file_url,meta={'item': item})

    def item_completed(self, results, item, info):
        file_paths = [x['path'] for ok, x in results if ok]
        if not file_paths:
            raise DropItem("Item contains no files")
        item['file_path'] = file_paths
        return item

    def file_path(self, request, response=None, info=None):
        #image_name = request.meta['item']['name'][0].split(' ')[1]
        name = request.meta['item']['name'][0]
        if not name:
            raise DropItem("fuck name")
        guid = request.url.split('/')[-1]
        return '%s/%s' % (name,guid)