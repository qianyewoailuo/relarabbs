<?php

namespace App\Handlers;

class ImageUploadHandler
{
    // 上传文件后缀限制
    protected $allowed_ext = ["png", "jpg", "jpeg", "gif",'bmp'];

    // 保存上传图片
    public function save($file, $folder, $file_prefix='avatar')
    {
        // 存储图片文件夹规则为例 uploads/images/avatar/201906/28/
        // 存储文件夹名称
        $folder_name = "uploads/images/$folder/" . date("Ym/d", time());
        // 完整物理路径
        $upload_path = public_path() . '/' . $folder_name;
        // 获取后缀名
        $extension = strtolower($file->getClientOriginalExtension())?:'png';
        // 如果上传的不是图片将终止操作
        if (! in_array($extension,$this->allowed_ext)){
            return false;
        }
        // 拼接文件名,加前缀是为了增加辨析度，前缀可以是相关数据模型的 ID
        $filename = $file_prefix.'_'.time().'_'.str_random(10).'.'.$extension;

        // 将图片移动到我们的目标存储路径中
        $file->move($upload_path, $filename);
        // 返回图片网址信息
        return [
            'path' => config('app.url') . "/$folder_name/$filename"
        ];

    }
}
