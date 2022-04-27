<?php

namespace Sang\Basicfun;

class CommonFun{
	/**
   * 上传图片
   * @param $request
   * @return path
   */
  public static function imgload($dirname,$file)
  {
    try {
      //文件是否上传成功               
      if($file->isValid()){   //判断文件是否上传成功
        $originalName = $file->getClientOriginalName(); //源文件名
        $ext = $file->getClientOriginalExtension();    //文件拓展名
        $type = $file->getClientMimeType(); //文件类型
        $realPath = $file->getRealPath();   //临时文件的绝对路径
        $fileName =$dirname.'/'.date('m').'/'.date('d').'/'.date('His').rand(0000,9999).'.'.$ext;  //新文件名                  
        $bool = Storage::disk('public')->put($fileName,file_get_contents($realPath));   //传成功返回bool值
      }    
      if($fileName){
      	return ['Code'=>0,'data'=>'storage/'.$fileName];
      }else{
        return ['Code'=>1,'data'=>''];
      }
    } catch (\Exception $exception) {
      return ['Code'=>1,'data'=>''];
    }
  }
  /**
   * 保存base64数据为图片
   * @param $request
   * @return path
   */
  public static function getBaseAndSaveImg($dirname,$imgdata)
  {
    try {
      $img = str_replace('data:image/png;base64,','',$imgdata);
      $img = str_replace('data:image/jpeg;base64,','',$imgdata);
      $img = str_replace(' ', '+', $img);
      $data = base64_decode($img);
      $path=$dirname.'/'.date('m').'/'.date('d').'/'.date('His').rand(0000,9999).'.png';  //新图片路径
      //$r=file_get_contents(storage_path('app/public/').$path,$data);
      $r =Storage::disk('public')->put($path,$data);// 生成图片
      if (!$r) {
          return ['Code'=>1,'data'=>''];
      }else{
          return ['Code'=>0,'data'=>'storage/'.$fileName];
      }
    } catch (\Exception $exception) {
      return ['Code'=>1,'data'=>''];
    }
  }
}
