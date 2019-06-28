<?php

namespace App\Handlers;

use Intervention\Image\Facades\Image;

class ImageUploadHandler
{
    // 上传文件后缀限制
    protected $allowed_ext = ["png", "jpg", "jpeg", "gif", 'bmp'];

    // 保存上传图片
    public function save($file, $folder, $file_prefix = 'avatar', $max_width = false)
    {
        // 存储图片文件夹规则为例 uploads/images/avatar/201906/28/
        // 存储文件夹名称
        $folder_name = "uploads/images/$folder/" . date("Ym/d", time());
        // 完整物理路径
        $upload_path = public_path() . '/' . $folder_name;
        // 获取后缀名
        $extension = strtolower($file->getClientOriginalExtension()) ?: 'png';
        // 如果上传的不是图片将终止操作
        if (!in_array($extension, $this->allowed_ext)) {
            return false;
        }
        // 拼接文件名,加前缀是为了增加辨析度，前缀可以是相关数据模型的 ID
        $filename = $file_prefix . '_' . time() . '_' . str_random(10) . '.' . $extension;

        // 将图片移动到我们的目标存储路径中
        $file->move($upload_path, $filename);

        // 如果限制了图片分辨率即需要进行图片裁剪
        if($max_width && $extension != 'gif'){
            // 此类中封装的函数，用于裁剪图片
            $this->reduceSize($upload_path . '/' . $filename, $max_width);
        }

        // 返回图片网址信息
        return [
            'path' => config('app.url') . "/$folder_name/$filename"
        ];
    }

    // 裁剪图片
    public function reduceSize($file_path,$max_width)
    {
        // 实例化图片对象,传参是图片的物理路径地址
        $image = Image::make($file_path);
        // 进行大小调整
        $image->resize($max_width,null,function($constraint){
            // 设置等比缩放
            $constraint->aspectRatio();
            // 防止截图时图片尺寸变大
            $constraint->upsize();
        });

        // 保存修改后的图片
        $image->save();
    }
}
