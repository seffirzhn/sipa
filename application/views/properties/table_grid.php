                        <div class="table-responsive layout-table">
                          <table class="table table-bordered table-hover table-striped dataTable">
                            <thead>
                              <tr>
                                <?php 
                                  foreach ($column as $key => $value) {
                                    $width  = $value["width"].(((int)$value["width"]!=0)?"%":"");
                                    $sort   = (($value["sorting"]=="true" && !empty($value["type_sort"]))? $value['type_sort'] : 'asc');   
                                    $addclass = (!empty($value["addclass"]))?$value["addclass"]:"";
                                ?>
                                  <th width="<?php echo $width?>" data-class="<?php echo $addclass ?>" column="<?php echo $key ?>" data-sort="<?php echo $value['sorting']?>" type-sort="<?php echo $sort?>" data-align="<?php echo $value['align']?>" >
                                    <?php echo $value['text']?>
                                  </th>
                                <?php
                                  }
                                ?>
                              </tr>
                            </thead>
                            <tbody class="table-grid">
                            </tbody>
                          </table>
                        </div>