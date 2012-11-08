<?php
defined('WYSIJA') or die('Restricted access'); class WYSIJA_view_front_confirm extends WYSIJA_view_front { function WYSIJA_view_front_confirm(){ $this->model=&WYSIJA::get("user","model"); } function subscriptions($data){ $this->addScripts(false); $content=$this->messages(); $formObj=&WYSIJA::get("forms","helper"); $content.='<form id="widget-wysija-nl" method="post" action="" class="form-valid">'; $content.='<table class="form-table">
                <tbody>'; $content.='<tr>
                        <th scope="row" colspan="2">'; $content.='<h3>'.__('Subscriber details',WYSIJA).'</h3>'; $content.='</th>
                    </tr>'; $content.='<tr>
                        <th scope="row">
                            <label for="fname">'.__('First name',WYSIJA).'</label>
                        </th>
                        <td>
                            <input type="text" size="40" class="validate[required]" id="fname" value="'.esc_attr($data['user']['details']['firstname']).'" name="wysija[user][firstname]" />
                        </td>
                    </tr>'; $content.='<tr>
                        <th scope="row">
                            <label for="lname">'.__('Last name',WYSIJA).'</label>
                        </th>
                        <td>
                            <input type="text" size="40" class="validate[required]" id="lname" value="'.esc_attr($data['user']['details']['lastname']).'" name="wysija[user][lastname]" />
                        </td>
                    </tr>'; $content.='<tr>
                        <th scope="row">
                            <label for="status">'.__('Status',WYSIJA).'</label>
                        </th>
                        <td>
                            '.$formObj->radios( array('id'=>'status', 'name'=>'wysija[user][status]'), array("-1"=>" ".__("Unsubscribed",WYSIJA)." ","1"=>" ".__("Subscribed",WYSIJA)." "), $data['user']['details']['status'], ' class="validate[required]" ').'
                        </td>
                    </tr>'; $content.=$this->customFields(); $content.='<tr></tr><tr>
            <th scope="row" colspan="2">'; $content.='<h3>'.__('List of newsletter subscriptions',WYSIJA).'</h3>'; $field="lists-"; $content.='</th>'; $fieldHTML= ''; $field="list"; $valuefield=array(); foreach($data['user']['lists'] as $list){ $valuefield[$list['list_id']]=$list; } $fieldHTML= ''; $field="list"; $valuefield=array(); if($data['user']){ foreach($data['user']['lists'] as $list){ $valuefield[$list['list_id']]=$list; } } $formObj=&WYSIJA::get("forms","helper"); foreach($data['list'] as $list){ $checked=false; $extratext=$extraCheckbox=''; if(isset($valuefield[$list['list_id']])) { if($valuefield[$list['list_id']]['unsub_date']<=0){ $checked=true; } } $labelHTML= '<label for="'.$field.$list['list_id'].'">'.$list['name'].'</label>'; $fieldHTML=$formObj->checkbox( array('id'=>$field.$list['list_id'],'name'=>"wysija[user_list][list_id][]"),$list['list_id'],$checked,$extraCheckbox).$labelHTML; $content.= "<tr><td colspan='2'>". $fieldHTML."</td></tr>"; } $content.="</tbody></table>"; $content.='<p class="submit">
                        '.$this->secure(array('controller'=>"confirm",'action'=>"save", 'id'=> $data['user']['details']['user_id']),false,false).'
                        <input type="hidden" name="wysija[user][user_id]" id="user_id" value="'.esc_attr($data['user']['details']['user_id']).'" />
                       <input type="hidden" name="id" id="user_id2" value="'.esc_attr($data['user']['details']['user_id']).'" />
                        <input type="hidden" value="save" name="action" />
                        <input type="submit" value="'.esc_attr(__('Save',WYSIJA)).'" class="button-primary wysija">
                    </p>'; $content.="</form>"; return $content; } function customFields(){ } } 