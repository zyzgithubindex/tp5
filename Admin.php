<?php
namespace app\admin\controller;

class Admin extends Base
{
    //商品列表
    public function index()
    {
    	$list=db('admin')->alias('a')->field('a.*,level_name')->join('tp5_level b','a.level=b.id','LEFT')->order('a.id DESC')->paginate(5);
        $this->assign(['list'=>$list]);
        return $this->fetch();
    }   
    //添加
    public function add()
    {
        if(request()->isPost()){
            $post=json_decode(input('post.datajson'),true);
            $user=db('Admin')->where('user',$post['user'])->find();

            if($user){
                $data['code']=0;
                $data['msg']='当前管理员已存在';
            }else{
                $post['pwd']=md5($post['pwd']);
                $res=db('admin')->field(true)->insert($post);
                $data=$this->Ajax_return($res);
            }
            return json($data);
        }
        $level=db('level')->where('id','neq',1)->select();
        $this->assign(['level'=>$level]);
        return $this->fetch();
    }

    //商品的修改
    public function edit()
    {
        $id=input('id');
        if ($id==1) {
           $this->redirect('Admin/index');//重定向
        }
        $res=db('admin')->where('id',$id)->find();
        if(request()->isPost()){
            $post=json_decode(input('post.datajson'),true);
            $old=db('admin')->where('id',$post['id'])->find();
        if ($post['pwd']!=$old['pwd']) {
            $post['pwd']=md5($post['pwd']);
            }
            $edit=db('admin')->field(true)->where('id',$post['id'])->update($post);
            return json($this->Ajax_return($edit));
        }
        $level=db('level')->where('id','neq',1)->select();
        $this->assign(['level'=>$level,'res'=>$res]);
        return $this->fetch();
    }

    //商品单条删除
    public function del()
    {
        if(request()->isget()){
            $id=input('id');
            if ($id==1) {
                $data['code']=0;
                $data['msg']='暂无权限操作';
            }else{
                $res=db('admin')->delete($id);
                $data=$this->Ajax_return($res);
            }
            return json($data);
        }
    }
        //商品多条删除
    public function delall()
    {
        if(request()->ispost()){
            $ids=explode(',',input('ids'));
            if (in_array(1,$ids)) {
                $data['code']=0;
                $data['msg']='暂无权限操作';
            }else{
                $res=db('admin')->delete($id);
                $data=$this->Ajax_return($res);
            }
            $res=db('admin')->delete($ids);
            return json($data);
        }
    }
    //更改推荐状态
    public function change_allow()
    {
        if (request()->isPost()){
            $post=input('post.');
            $res=db('admin')->where('id',$post['id'])->setField('allow',$post['checked']);
            return json($this->Ajax_return($res)); 
        }
    }
}
	