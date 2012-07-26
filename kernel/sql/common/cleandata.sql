INSERT INTO ezcobj_state (
  default_language_id,
  group_id,
  id,
  identifier,
  language_mask,
  priority
) VALUES (
  2,
  2,
  1,
  'not_locked',
  3,
  0
);
INSERT INTO ezcobj_state (
  default_language_id,
  group_id,
  id,
  identifier,
  language_mask,
  priority
) VALUES (
  2,
  2,
  2,
  'locked',
  3,
  1
);

INSERT INTO ezcobj_state_group (
  default_language_id,
  id,
  identifier,
  language_mask
) VALUES (
  2,
  2,
  'ez_lock',
  3
);

INSERT INTO ezcobj_state_group_language (
  contentobject_state_group_id,
  description,
  language_id,
  real_language_id,
  name
) VALUES (
  2,
  '',
  3,
  2,
  'Lock'
);

INSERT INTO ezcobj_state_language (
  contentobject_state_id,
  description,
  language_id,
  name
) VALUES (
  1,
  '',
  3,
  'Not locked'
);
INSERT INTO ezcobj_state_language (
  contentobject_state_id,
  description,
  language_id,
  name
) VALUES (
  2,
  '',
  3,
  'Locked'
);

INSERT INTO ezcobj_state_link (
  contentobject_id,
  contentobject_state_id
) VALUES (
  1,
  1
);
INSERT INTO ezcobj_state_link (
  contentobject_id,
  contentobject_state_id
) VALUES (
  4,
  1
);
INSERT INTO ezcobj_state_link (
  contentobject_id,
  contentobject_state_id
) VALUES (
  10,
  1
);
INSERT INTO ezcobj_state_link (
  contentobject_id,
  contentobject_state_id
) VALUES (
  11,
  1
);
INSERT INTO ezcobj_state_link (
  contentobject_id,
  contentobject_state_id
) VALUES (
  12,
  1
);
INSERT INTO ezcobj_state_link (
  contentobject_id,
  contentobject_state_id
) VALUES (
  13,
  1
);
INSERT INTO ezcobj_state_link (
  contentobject_id,
  contentobject_state_id
) VALUES (
  14,
  1
);
INSERT INTO ezcobj_state_link (
  contentobject_id,
  contentobject_state_id
) VALUES (
  41,
  1
);
INSERT INTO ezcobj_state_link (
  contentobject_id,
  contentobject_state_id
) VALUES (
  42,
  1
);
INSERT INTO ezcobj_state_link (
  contentobject_id,
  contentobject_state_id
) VALUES (
  45,
  1
);
INSERT INTO ezcobj_state_link (
  contentobject_id,
  contentobject_state_id
) VALUES (
  49,
  1
);
INSERT INTO ezcobj_state_link (
  contentobject_id,
  contentobject_state_id
) VALUES (
  50,
  1
);
INSERT INTO ezcobj_state_link (
  contentobject_id,
  contentobject_state_id
) VALUES (
  51,
  1
);
INSERT INTO ezcobj_state_link (
  contentobject_id,
  contentobject_state_id
) VALUES (
  52,
  1
);
INSERT INTO ezcobj_state_link (
  contentobject_id,
  contentobject_state_id
) VALUES (
  54,
  1
);
INSERT INTO ezcobj_state_link (
  contentobject_id,
  contentobject_state_id
) VALUES (
  56,
  1
);

INSERT INTO ezcontent_language (
  disabled,
  id,
  locale,
  name
) VALUES (
  0,
  2,
  'eng-GB',
  'English (United Kingdom)'
);

INSERT INTO ezcontentclass (
  always_available,
  contentobject_name,
  created,
  creator_id,
  id,
  identifier,
  initial_language_id,
  is_container,
  language_mask,
  modified,
  modifier_id,
  remote_id,
  serialized_description_list,
  serialized_name_list,
  sort_field,
  sort_order,
  url_alias_name,
  version
) VALUES (
  1,
  '<short_name|name>',
  1024392098,
  14,
  1,
  'folder',
  2,
  1,
  3,
  1082454875,
  14,
  'a3d405b81be900468eb153d774f4f0d2',
  NULL,
  'a:2:{s:6:\"eng-GB\";s:6:\"Folder\";s:16:\"always-available\";s:6:\"eng-GB\";}',
  1,
  1,
  NULL,
  0
);
INSERT INTO ezcontentclass (
  always_available,
  contentobject_name,
  created,
  creator_id,
  id,
  identifier,
  initial_language_id,
  is_container,
  language_mask,
  modified,
  modifier_id,
  remote_id,
  serialized_description_list,
  serialized_name_list,
  sort_field,
  sort_order,
  url_alias_name,
  version
) VALUES (
  0,
  '<short_title|title>',
  1024392098,
  14,
  2,
  'article',
  2,
  1,
  3,
  1082454989,
  14,
  'c15b600eb9198b1924063b5a68758232',
  NULL,
  'a:2:{s:6:\"eng-GB\";s:7:\"Article\";s:16:\"always-available\";s:6:\"eng-GB\";}',
  1,
  1,
  NULL,
  0
);
INSERT INTO ezcontentclass (
  always_available,
  contentobject_name,
  created,
  creator_id,
  id,
  identifier,
  initial_language_id,
  is_container,
  language_mask,
  modified,
  modifier_id,
  remote_id,
  serialized_description_list,
  serialized_name_list,
  sort_field,
  sort_order,
  url_alias_name,
  version
) VALUES (
  1,
  '<name>',
  1024392098,
  14,
  3,
  'user_group',
  2,
  1,
  3,
  1048494743,
  14,
  '25b4268cdcd01921b808a0d854b877ef',
  NULL,
  'a:2:{s:6:\"eng-GB\";s:10:\"User group\";s:16:\"always-available\";s:6:\"eng-GB\";}',
  1,
  1,
  NULL,
  0
);
INSERT INTO ezcontentclass (
  always_available,
  contentobject_name,
  created,
  creator_id,
  id,
  identifier,
  initial_language_id,
  is_container,
  language_mask,
  modified,
  modifier_id,
  remote_id,
  serialized_description_list,
  serialized_name_list,
  sort_field,
  sort_order,
  url_alias_name,
  version
) VALUES (
  1,
  '<first_name> <last_name>',
  1024392098,
  14,
  4,
  'user',
  2,
  0,
  3,
  1082018364,
  14,
  '40faa822edc579b02c25f6bb7beec3ad',
  NULL,
  'a:2:{s:6:\"eng-GB\";s:4:\"User\";s:16:\"always-available\";s:6:\"eng-GB\";}',
  1,
  1,
  NULL,
  0
);
INSERT INTO ezcontentclass (
  always_available,
  contentobject_name,
  created,
  creator_id,
  id,
  identifier,
  initial_language_id,
  is_container,
  language_mask,
  modified,
  modifier_id,
  remote_id,
  serialized_description_list,
  serialized_name_list,
  sort_field,
  sort_order,
  url_alias_name,
  version
) VALUES (
  1,
  '<name>',
  1031484992,
  8,
  5,
  'image',
  2,
  0,
  3,
  1048494784,
  14,
  'f6df12aa74e36230eb675f364fccd25a',
  NULL,
  'a:2:{s:6:\"eng-GB\";s:5:\"Image\";s:16:\"always-available\";s:6:\"eng-GB\";}',
  1,
  1,
  NULL,
  0
);
INSERT INTO ezcontentclass (
  always_available,
  contentobject_name,
  created,
  creator_id,
  id,
  identifier,
  initial_language_id,
  is_container,
  language_mask,
  modified,
  modifier_id,
  remote_id,
  serialized_description_list,
  serialized_name_list,
  sort_field,
  sort_order,
  url_alias_name,
  version
) VALUES (
  0,
  '<name>',
  1052385361,
  14,
  11,
  'link',
  2,
  0,
  3,
  1082455072,
  14,
  '74ec6507063150bc813549b22534ad48',
  NULL,
  'a:2:{s:6:\"eng-GB\";s:4:\"Link\";s:16:\"always-available\";s:6:\"eng-GB\";}',
  1,
  1,
  NULL,
  0
);
INSERT INTO ezcontentclass (
  always_available,
  contentobject_name,
  created,
  creator_id,
  id,
  identifier,
  initial_language_id,
  is_container,
  language_mask,
  modified,
  modifier_id,
  remote_id,
  serialized_description_list,
  serialized_name_list,
  sort_field,
  sort_order,
  url_alias_name,
  version
) VALUES (
  1,
  '<name>',
  1052385472,
  14,
  12,
  'file',
  2,
  0,
  3,
  1052385669,
  14,
  '637d58bfddf164627bdfd265733280a0',
  NULL,
  'a:2:{s:6:\"eng-GB\";s:4:\"File\";s:16:\"always-available\";s:6:\"eng-GB\";}',
  1,
  1,
  NULL,
  0
);
INSERT INTO ezcontentclass (
  always_available,
  contentobject_name,
  created,
  creator_id,
  id,
  identifier,
  initial_language_id,
  is_container,
  language_mask,
  modified,
  modifier_id,
  remote_id,
  serialized_description_list,
  serialized_name_list,
  sort_field,
  sort_order,
  url_alias_name,
  version
) VALUES (
  0,
  '<subject>',
  1052385685,
  14,
  13,
  'comment',
  2,
  0,
  3,
  1082455144,
  14,
  '000c14f4f475e9f2955dedab72799941',
  NULL,
  'a:2:{s:6:\"eng-GB\";s:7:\"Comment\";s:16:\"always-available\";s:6:\"eng-GB\";}',
  1,
  1,
  NULL,
  0
);
INSERT INTO ezcontentclass (
  always_available,
  contentobject_name,
  created,
  creator_id,
  id,
  identifier,
  initial_language_id,
  is_container,
  language_mask,
  modified,
  modifier_id,
  remote_id,
  serialized_description_list,
  serialized_name_list,
  sort_field,
  sort_order,
  url_alias_name,
  version
) VALUES (
  1,
  '<name>',
  1081858024,
  14,
  14,
  'common_ini_settings',
  2,
  0,
  3,
  1081858024,
  14,
  'ffedf2e73b1ea0c3e630e42e2db9c900',
  NULL,
  'a:2:{s:6:\"eng-GB\";s:19:\"Common ini settings\";s:16:\"always-available\";s:6:\"eng-GB\";}',
  1,
  1,
  NULL,
  0
);
INSERT INTO ezcontentclass (
  always_available,
  contentobject_name,
  created,
  creator_id,
  id,
  identifier,
  initial_language_id,
  is_container,
  language_mask,
  modified,
  modifier_id,
  remote_id,
  serialized_description_list,
  serialized_name_list,
  sort_field,
  sort_order,
  url_alias_name,
  version
) VALUES (
  1,
  '<title>',
  1081858045,
  14,
  15,
  'template_look',
  2,
  0,
  3,
  1081858045,
  14,
  '59b43cd9feaaf0e45ac974fb4bbd3f92',
  NULL,
  'a:2:{s:6:\"eng-GB\";s:13:\"Template look\";s:16:\"always-available\";s:6:\"eng-GB\";}',
  1,
  1,
  NULL,
  0
);

INSERT INTO ezcontentclass_attribute (
  can_translate,
  category,
  contentclass_id,
  data_float1,
  data_float2,
  data_float3,
  data_float4,
  data_int1,
  data_int2,
  data_int3,
  data_int4,
  data_text1,
  data_text2,
  data_text3,
  data_text4,
  data_text5,
  data_type_string,
  id,
  identifier,
  is_information_collector,
  is_required,
  is_searchable,
  placement,
  serialized_data_text,
  serialized_description_list,
  serialized_name_list,
  version
) VALUES (
  1,
  '',
  2,
  0,
  0,
  0,
  0,
  255,
  0,
  0,
  0,
  'New article',
  '',
  '',
  '',
  '',
  'ezstring',
  1,
  'title',
  0,
  1,
  1,
  1,
  NULL,
  NULL,
  'a:2:{s:6:\"eng-GB\";s:5:\"Title\";s:16:\"always-available\";s:6:\"eng-GB\";}',
  0
);
INSERT INTO ezcontentclass_attribute (
  can_translate,
  category,
  contentclass_id,
  data_float1,
  data_float2,
  data_float3,
  data_float4,
  data_int1,
  data_int2,
  data_int3,
  data_int4,
  data_text1,
  data_text2,
  data_text3,
  data_text4,
  data_text5,
  data_type_string,
  id,
  identifier,
  is_information_collector,
  is_required,
  is_searchable,
  placement,
  serialized_data_text,
  serialized_description_list,
  serialized_name_list,
  version
) VALUES (
  1,
  '',
  1,
  0,
  0,
  0,
  0,
  255,
  0,
  0,
  0,
  'Folder',
  '',
  '',
  '',
  '',
  'ezstring',
  4,
  'name',
  0,
  1,
  1,
  1,
  NULL,
  NULL,
  'a:2:{s:6:\"eng-GB\";s:4:\"Name\";s:16:\"always-available\";s:6:\"eng-GB\";}',
  0
);
INSERT INTO ezcontentclass_attribute (
  can_translate,
  category,
  contentclass_id,
  data_float1,
  data_float2,
  data_float3,
  data_float4,
  data_int1,
  data_int2,
  data_int3,
  data_int4,
  data_text1,
  data_text2,
  data_text3,
  data_text4,
  data_text5,
  data_type_string,
  id,
  identifier,
  is_information_collector,
  is_required,
  is_searchable,
  placement,
  serialized_data_text,
  serialized_description_list,
  serialized_name_list,
  version
) VALUES (
  1,
  '',
  3,
  0,
  0,
  0,
  0,
  255,
  0,
  0,
  0,
  '',
  '',
  '',
  '',
  NULL,
  'ezstring',
  6,
  'name',
  0,
  1,
  1,
  1,
  NULL,
  NULL,
  'a:2:{s:6:\"eng-GB\";s:4:\"Name\";s:16:\"always-available\";s:6:\"eng-GB\";}',
  0
);
INSERT INTO ezcontentclass_attribute (
  can_translate,
  category,
  contentclass_id,
  data_float1,
  data_float2,
  data_float3,
  data_float4,
  data_int1,
  data_int2,
  data_int3,
  data_int4,
  data_text1,
  data_text2,
  data_text3,
  data_text4,
  data_text5,
  data_type_string,
  id,
  identifier,
  is_information_collector,
  is_required,
  is_searchable,
  placement,
  serialized_data_text,
  serialized_description_list,
  serialized_name_list,
  version
) VALUES (
  1,
  '',
  3,
  0,
  0,
  0,
  0,
  255,
  0,
  0,
  0,
  '',
  '',
  '',
  '',
  NULL,
  'ezstring',
  7,
  'description',
  0,
  0,
  1,
  2,
  NULL,
  NULL,
  'a:2:{s:6:\"eng-GB\";s:11:\"Description\";s:16:\"always-available\";s:6:\"eng-GB\";}',
  0
);
INSERT INTO ezcontentclass_attribute (
  can_translate,
  category,
  contentclass_id,
  data_float1,
  data_float2,
  data_float3,
  data_float4,
  data_int1,
  data_int2,
  data_int3,
  data_int4,
  data_text1,
  data_text2,
  data_text3,
  data_text4,
  data_text5,
  data_type_string,
  id,
  identifier,
  is_information_collector,
  is_required,
  is_searchable,
  placement,
  serialized_data_text,
  serialized_description_list,
  serialized_name_list,
  version
) VALUES (
  1,
  '',
  4,
  0,
  0,
  0,
  0,
  255,
  0,
  0,
  0,
  '',
  '',
  '',
  '',
  '',
  'ezstring',
  8,
  'first_name',
  0,
  1,
  1,
  1,
  NULL,
  NULL,
  'a:2:{s:6:\"eng-GB\";s:10:\"First name\";s:16:\"always-available\";s:6:\"eng-GB\";}',
  0
);
INSERT INTO ezcontentclass_attribute (
  can_translate,
  category,
  contentclass_id,
  data_float1,
  data_float2,
  data_float3,
  data_float4,
  data_int1,
  data_int2,
  data_int3,
  data_int4,
  data_text1,
  data_text2,
  data_text3,
  data_text4,
  data_text5,
  data_type_string,
  id,
  identifier,
  is_information_collector,
  is_required,
  is_searchable,
  placement,
  serialized_data_text,
  serialized_description_list,
  serialized_name_list,
  version
) VALUES (
  1,
  '',
  4,
  0,
  0,
  0,
  0,
  255,
  0,
  0,
  0,
  '',
  '',
  '',
  '',
  '',
  'ezstring',
  9,
  'last_name',
  0,
  1,
  1,
  2,
  NULL,
  NULL,
  'a:2:{s:6:\"eng-GB\";s:9:\"Last name\";s:16:\"always-available\";s:6:\"eng-GB\";}',
  0
);
INSERT INTO ezcontentclass_attribute (
  can_translate,
  category,
  contentclass_id,
  data_float1,
  data_float2,
  data_float3,
  data_float4,
  data_int1,
  data_int2,
  data_int3,
  data_int4,
  data_text1,
  data_text2,
  data_text3,
  data_text4,
  data_text5,
  data_type_string,
  id,
  identifier,
  is_information_collector,
  is_required,
  is_searchable,
  placement,
  serialized_data_text,
  serialized_description_list,
  serialized_name_list,
  version
) VALUES (
  0,
  '',
  4,
  0,
  0,
  0,
  0,
  0,
  0,
  0,
  0,
  '',
  '',
  '',
  '',
  '',
  'ezuser',
  12,
  'user_account',
  0,
  1,
  1,
  3,
  NULL,
  NULL,
  'a:2:{s:6:\"eng-GB\";s:12:\"User account\";s:16:\"always-available\";s:6:\"eng-GB\";}',
  0
);
INSERT INTO ezcontentclass_attribute (
  can_translate,
  category,
  contentclass_id,
  data_float1,
  data_float2,
  data_float3,
  data_float4,
  data_int1,
  data_int2,
  data_int3,
  data_int4,
  data_text1,
  data_text2,
  data_text3,
  data_text4,
  data_text5,
  data_type_string,
  id,
  identifier,
  is_information_collector,
  is_required,
  is_searchable,
  placement,
  serialized_data_text,
  serialized_description_list,
  serialized_name_list,
  version
) VALUES (
  1,
  '',
  5,
  0,
  0,
  0,
  0,
  150,
  0,
  0,
  0,
  '',
  '',
  '',
  '',
  NULL,
  'ezstring',
  116,
  'name',
  0,
  1,
  1,
  1,
  NULL,
  NULL,
  'a:2:{s:6:\"eng-GB\";s:4:\"Name\";s:16:\"always-available\";s:6:\"eng-GB\";}',
  0
);
INSERT INTO ezcontentclass_attribute (
  can_translate,
  category,
  contentclass_id,
  data_float1,
  data_float2,
  data_float3,
  data_float4,
  data_int1,
  data_int2,
  data_int3,
  data_int4,
  data_text1,
  data_text2,
  data_text3,
  data_text4,
  data_text5,
  data_type_string,
  id,
  identifier,
  is_information_collector,
  is_required,
  is_searchable,
  placement,
  serialized_data_text,
  serialized_description_list,
  serialized_name_list,
  version
) VALUES (
  1,
  '',
  5,
  0,
  0,
  0,
  0,
  10,
  0,
  0,
  0,
  '',
  '',
  '',
  '',
  NULL,
  'ezxmltext',
  117,
  'caption',
  0,
  0,
  1,
  2,
  NULL,
  NULL,
  'a:2:{s:6:\"eng-GB\";s:7:\"Caption\";s:16:\"always-available\";s:6:\"eng-GB\";}',
  0
);
INSERT INTO ezcontentclass_attribute (
  can_translate,
  category,
  contentclass_id,
  data_float1,
  data_float2,
  data_float3,
  data_float4,
  data_int1,
  data_int2,
  data_int3,
  data_int4,
  data_text1,
  data_text2,
  data_text3,
  data_text4,
  data_text5,
  data_type_string,
  id,
  identifier,
  is_information_collector,
  is_required,
  is_searchable,
  placement,
  serialized_data_text,
  serialized_description_list,
  serialized_name_list,
  version
) VALUES (
  1,
  '',
  5,
  0,
  0,
  0,
  0,
  2,
  0,
  0,
  0,
  '',
  '',
  '',
  '',
  NULL,
  'ezimage',
  118,
  'image',
  0,
  0,
  0,
  3,
  NULL,
  NULL,
  'a:2:{s:6:\"eng-GB\";s:5:\"Image\";s:16:\"always-available\";s:6:\"eng-GB\";}',
  0
);
INSERT INTO ezcontentclass_attribute (
  can_translate,
  category,
  contentclass_id,
  data_float1,
  data_float2,
  data_float3,
  data_float4,
  data_int1,
  data_int2,
  data_int3,
  data_int4,
  data_text1,
  data_text2,
  data_text3,
  data_text4,
  data_text5,
  data_type_string,
  id,
  identifier,
  is_information_collector,
  is_required,
  is_searchable,
  placement,
  serialized_data_text,
  serialized_description_list,
  serialized_name_list,
  version
) VALUES (
  1,
  '',
  1,
  0,
  0,
  0,
  0,
  5,
  0,
  0,
  0,
  '',
  '',
  '',
  '',
  '',
  'ezxmltext',
  119,
  'short_description',
  0,
  0,
  1,
  3,
  NULL,
  NULL,
  'a:2:{s:6:\"eng-GB\";s:17:\"Short description\";s:16:\"always-available\";s:6:\"eng-GB\";}',
  0
);
INSERT INTO ezcontentclass_attribute (
  can_translate,
  category,
  contentclass_id,
  data_float1,
  data_float2,
  data_float3,
  data_float4,
  data_int1,
  data_int2,
  data_int3,
  data_int4,
  data_text1,
  data_text2,
  data_text3,
  data_text4,
  data_text5,
  data_type_string,
  id,
  identifier,
  is_information_collector,
  is_required,
  is_searchable,
  placement,
  serialized_data_text,
  serialized_description_list,
  serialized_name_list,
  version
) VALUES (
  1,
  '',
  2,
  0,
  0,
  0,
  0,
  10,
  0,
  0,
  0,
  '',
  '',
  '',
  '',
  '',
  'ezxmltext',
  120,
  'intro',
  0,
  1,
  1,
  4,
  NULL,
  NULL,
  'a:2:{s:6:\"eng-GB\";s:5:\"Intro\";s:16:\"always-available\";s:6:\"eng-GB\";}',
  0
);
INSERT INTO ezcontentclass_attribute (
  can_translate,
  category,
  contentclass_id,
  data_float1,
  data_float2,
  data_float3,
  data_float4,
  data_int1,
  data_int2,
  data_int3,
  data_int4,
  data_text1,
  data_text2,
  data_text3,
  data_text4,
  data_text5,
  data_type_string,
  id,
  identifier,
  is_information_collector,
  is_required,
  is_searchable,
  placement,
  serialized_data_text,
  serialized_description_list,
  serialized_name_list,
  version
) VALUES (
  1,
  '',
  2,
  0,
  0,
  0,
  0,
  20,
  0,
  0,
  0,
  '',
  '',
  '',
  '',
  '',
  'ezxmltext',
  121,
  'body',
  0,
  0,
  1,
  5,
  NULL,
  NULL,
  'a:2:{s:6:\"eng-GB\";s:4:\"Body\";s:16:\"always-available\";s:6:\"eng-GB\";}',
  0
);
INSERT INTO ezcontentclass_attribute (
  can_translate,
  category,
  contentclass_id,
  data_float1,
  data_float2,
  data_float3,
  data_float4,
  data_int1,
  data_int2,
  data_int3,
  data_int4,
  data_text1,
  data_text2,
  data_text3,
  data_text4,
  data_text5,
  data_type_string,
  id,
  identifier,
  is_information_collector,
  is_required,
  is_searchable,
  placement,
  serialized_data_text,
  serialized_description_list,
  serialized_name_list,
  version
) VALUES (
  0,
  '',
  2,
  0,
  0,
  0,
  0,
  0,
  0,
  0,
  0,
  '',
  '',
  '',
  '',
  '',
  'ezboolean',
  123,
  'enable_comments',
  0,
  0,
  0,
  6,
  NULL,
  NULL,
  'a:2:{s:6:\"eng-GB\";s:15:\"Enable comments\";s:16:\"always-available\";s:6:\"eng-GB\";}',
  0
);
INSERT INTO ezcontentclass_attribute (
  can_translate,
  category,
  contentclass_id,
  data_float1,
  data_float2,
  data_float3,
  data_float4,
  data_int1,
  data_int2,
  data_int3,
  data_int4,
  data_text1,
  data_text2,
  data_text3,
  data_text4,
  data_text5,
  data_type_string,
  id,
  identifier,
  is_information_collector,
  is_required,
  is_searchable,
  placement,
  serialized_data_text,
  serialized_description_list,
  serialized_name_list,
  version
) VALUES (
  1,
  '',
  11,
  0,
  0,
  0,
  0,
  255,
  0,
  0,
  0,
  '',
  '',
  '',
  '',
  '',
  'ezstring',
  143,
  'name',
  0,
  1,
  1,
  1,
  NULL,
  NULL,
  'a:2:{s:6:\"eng-GB\";s:4:\"Name\";s:16:\"always-available\";s:6:\"eng-GB\";}',
  0
);
INSERT INTO ezcontentclass_attribute (
  can_translate,
  category,
  contentclass_id,
  data_float1,
  data_float2,
  data_float3,
  data_float4,
  data_int1,
  data_int2,
  data_int3,
  data_int4,
  data_text1,
  data_text2,
  data_text3,
  data_text4,
  data_text5,
  data_type_string,
  id,
  identifier,
  is_information_collector,
  is_required,
  is_searchable,
  placement,
  serialized_data_text,
  serialized_description_list,
  serialized_name_list,
  version
) VALUES (
  1,
  '',
  11,
  0,
  0,
  0,
  0,
  20,
  0,
  0,
  0,
  '',
  '',
  '',
  '',
  '',
  'ezxmltext',
  144,
  'description',
  0,
  0,
  1,
  2,
  NULL,
  NULL,
  'a:2:{s:6:\"eng-GB\";s:11:\"Description\";s:16:\"always-available\";s:6:\"eng-GB\";}',
  0
);
INSERT INTO ezcontentclass_attribute (
  can_translate,
  category,
  contentclass_id,
  data_float1,
  data_float2,
  data_float3,
  data_float4,
  data_int1,
  data_int2,
  data_int3,
  data_int4,
  data_text1,
  data_text2,
  data_text3,
  data_text4,
  data_text5,
  data_type_string,
  id,
  identifier,
  is_information_collector,
  is_required,
  is_searchable,
  placement,
  serialized_data_text,
  serialized_description_list,
  serialized_name_list,
  version
) VALUES (
  1,
  '',
  11,
  0,
  0,
  0,
  0,
  0,
  0,
  0,
  0,
  '',
  '',
  '',
  '',
  '',
  'ezurl',
  145,
  'location',
  0,
  0,
  0,
  3,
  NULL,
  NULL,
  'a:2:{s:6:\"eng-GB\";s:8:\"Location\";s:16:\"always-available\";s:6:\"eng-GB\";}',
  0
);
INSERT INTO ezcontentclass_attribute (
  can_translate,
  category,
  contentclass_id,
  data_float1,
  data_float2,
  data_float3,
  data_float4,
  data_int1,
  data_int2,
  data_int3,
  data_int4,
  data_text1,
  data_text2,
  data_text3,
  data_text4,
  data_text5,
  data_type_string,
  id,
  identifier,
  is_information_collector,
  is_required,
  is_searchable,
  placement,
  serialized_data_text,
  serialized_description_list,
  serialized_name_list,
  version
) VALUES (
  1,
  '',
  12,
  0,
  0,
  0,
  0,
  0,
  0,
  0,
  0,
  'New file',
  '',
  '',
  '',
  NULL,
  'ezstring',
  146,
  'name',
  0,
  1,
  1,
  1,
  NULL,
  NULL,
  'a:2:{s:6:\"eng-GB\";s:4:\"Name\";s:16:\"always-available\";s:6:\"eng-GB\";}',
  0
);
INSERT INTO ezcontentclass_attribute (
  can_translate,
  category,
  contentclass_id,
  data_float1,
  data_float2,
  data_float3,
  data_float4,
  data_int1,
  data_int2,
  data_int3,
  data_int4,
  data_text1,
  data_text2,
  data_text3,
  data_text4,
  data_text5,
  data_type_string,
  id,
  identifier,
  is_information_collector,
  is_required,
  is_searchable,
  placement,
  serialized_data_text,
  serialized_description_list,
  serialized_name_list,
  version
) VALUES (
  1,
  '',
  12,
  0,
  0,
  0,
  0,
  10,
  0,
  0,
  0,
  '',
  '',
  '',
  '',
  NULL,
  'ezxmltext',
  147,
  'description',
  0,
  0,
  1,
  2,
  NULL,
  NULL,
  'a:2:{s:6:\"eng-GB\";s:11:\"Description\";s:16:\"always-available\";s:6:\"eng-GB\";}',
  0
);
INSERT INTO ezcontentclass_attribute (
  can_translate,
  category,
  contentclass_id,
  data_float1,
  data_float2,
  data_float3,
  data_float4,
  data_int1,
  data_int2,
  data_int3,
  data_int4,
  data_text1,
  data_text2,
  data_text3,
  data_text4,
  data_text5,
  data_type_string,
  id,
  identifier,
  is_information_collector,
  is_required,
  is_searchable,
  placement,
  serialized_data_text,
  serialized_description_list,
  serialized_name_list,
  version
) VALUES (
  1,
  '',
  12,
  0,
  0,
  0,
  0,
  0,
  0,
  0,
  0,
  '',
  '',
  '',
  '',
  NULL,
  'ezbinaryfile',
  148,
  'file',
  0,
  1,
  0,
  3,
  NULL,
  NULL,
  'a:2:{s:6:\"eng-GB\";s:4:\"File\";s:16:\"always-available\";s:6:\"eng-GB\";}',
  0
);
INSERT INTO ezcontentclass_attribute (
  can_translate,
  category,
  contentclass_id,
  data_float1,
  data_float2,
  data_float3,
  data_float4,
  data_int1,
  data_int2,
  data_int3,
  data_int4,
  data_text1,
  data_text2,
  data_text3,
  data_text4,
  data_text5,
  data_type_string,
  id,
  identifier,
  is_information_collector,
  is_required,
  is_searchable,
  placement,
  serialized_data_text,
  serialized_description_list,
  serialized_name_list,
  version
) VALUES (
  1,
  '',
  13,
  0,
  0,
  0,
  0,
  100,
  0,
  0,
  0,
  '',
  '',
  '',
  '',
  '',
  'ezstring',
  149,
  'subject',
  0,
  1,
  1,
  1,
  NULL,
  NULL,
  'a:2:{s:6:\"eng-GB\";s:7:\"Subject\";s:16:\"always-available\";s:6:\"eng-GB\";}',
  0
);
INSERT INTO ezcontentclass_attribute (
  can_translate,
  category,
  contentclass_id,
  data_float1,
  data_float2,
  data_float3,
  data_float4,
  data_int1,
  data_int2,
  data_int3,
  data_int4,
  data_text1,
  data_text2,
  data_text3,
  data_text4,
  data_text5,
  data_type_string,
  id,
  identifier,
  is_information_collector,
  is_required,
  is_searchable,
  placement,
  serialized_data_text,
  serialized_description_list,
  serialized_name_list,
  version
) VALUES (
  1,
  '',
  13,
  0,
  0,
  0,
  0,
  0,
  0,
  0,
  0,
  '',
  '',
  '',
  '',
  '',
  'ezstring',
  150,
  'author',
  0,
  1,
  1,
  2,
  NULL,
  NULL,
  'a:2:{s:6:\"eng-GB\";s:6:\"Author\";s:16:\"always-available\";s:6:\"eng-GB\";}',
  0
);
INSERT INTO ezcontentclass_attribute (
  can_translate,
  category,
  contentclass_id,
  data_float1,
  data_float2,
  data_float3,
  data_float4,
  data_int1,
  data_int2,
  data_int3,
  data_int4,
  data_text1,
  data_text2,
  data_text3,
  data_text4,
  data_text5,
  data_type_string,
  id,
  identifier,
  is_information_collector,
  is_required,
  is_searchable,
  placement,
  serialized_data_text,
  serialized_description_list,
  serialized_name_list,
  version
) VALUES (
  1,
  '',
  13,
  0,
  0,
  0,
  0,
  20,
  0,
  0,
  0,
  '',
  '',
  '',
  '',
  '',
  'eztext',
  151,
  'message',
  0,
  1,
  1,
  3,
  NULL,
  NULL,
  'a:2:{s:6:\"eng-GB\";s:7:\"Message\";s:16:\"always-available\";s:6:\"eng-GB\";}',
  0
);
INSERT INTO ezcontentclass_attribute (
  can_translate,
  category,
  contentclass_id,
  data_float1,
  data_float2,
  data_float3,
  data_float4,
  data_int1,
  data_int2,
  data_int3,
  data_int4,
  data_text1,
  data_text2,
  data_text3,
  data_text4,
  data_text5,
  data_type_string,
  id,
  identifier,
  is_information_collector,
  is_required,
  is_searchable,
  placement,
  serialized_data_text,
  serialized_description_list,
  serialized_name_list,
  version
) VALUES (
  1,
  '',
  2,
  0,
  0,
  0,
  0,
  255,
  0,
  0,
  0,
  '',
  '',
  '',
  '',
  '',
  'ezstring',
  152,
  'short_title',
  0,
  0,
  1,
  2,
  NULL,
  NULL,
  'a:2:{s:6:\"eng-GB\";s:11:\"Short title\";s:16:\"always-available\";s:6:\"eng-GB\";}',
  0
);
INSERT INTO ezcontentclass_attribute (
  can_translate,
  category,
  contentclass_id,
  data_float1,
  data_float2,
  data_float3,
  data_float4,
  data_int1,
  data_int2,
  data_int3,
  data_int4,
  data_text1,
  data_text2,
  data_text3,
  data_text4,
  data_text5,
  data_type_string,
  id,
  identifier,
  is_information_collector,
  is_required,
  is_searchable,
  placement,
  serialized_data_text,
  serialized_description_list,
  serialized_name_list,
  version
) VALUES (
  1,
  '',
  2,
  0,
  0,
  0,
  0,
  0,
  0,
  0,
  0,
  '',
  '',
  '',
  '',
  '',
  'ezauthor',
  153,
  'author',
  0,
  0,
  0,
  3,
  NULL,
  NULL,
  'a:2:{s:6:\"eng-GB\";s:6:\"Author\";s:16:\"always-available\";s:6:\"eng-GB\";}',
  0
);
INSERT INTO ezcontentclass_attribute (
  can_translate,
  category,
  contentclass_id,
  data_float1,
  data_float2,
  data_float3,
  data_float4,
  data_int1,
  data_int2,
  data_int3,
  data_int4,
  data_text1,
  data_text2,
  data_text3,
  data_text4,
  data_text5,
  data_type_string,
  id,
  identifier,
  is_information_collector,
  is_required,
  is_searchable,
  placement,
  serialized_data_text,
  serialized_description_list,
  serialized_name_list,
  version
) VALUES (
  1,
  '',
  2,
  0,
  0,
  0,
  0,
  0,
  0,
  0,
  0,
  '',
  '',
  '',
  '',
  '',
  'ezobjectrelation',
  154,
  'image',
  0,
  0,
  1,
  7,
  NULL,
  NULL,
  'a:2:{s:6:\"eng-GB\";s:5:\"Image\";s:16:\"always-available\";s:6:\"eng-GB\";}',
  0
);
INSERT INTO ezcontentclass_attribute (
  can_translate,
  category,
  contentclass_id,
  data_float1,
  data_float2,
  data_float3,
  data_float4,
  data_int1,
  data_int2,
  data_int3,
  data_int4,
  data_text1,
  data_text2,
  data_text3,
  data_text4,
  data_text5,
  data_type_string,
  id,
  identifier,
  is_information_collector,
  is_required,
  is_searchable,
  placement,
  serialized_data_text,
  serialized_description_list,
  serialized_name_list,
  version
) VALUES (
  1,
  '',
  1,
  0,
  0,
  0,
  0,
  100,
  0,
  0,
  0,
  '',
  '',
  '',
  '',
  '',
  'ezstring',
  155,
  'short_name',
  0,
  0,
  1,
  2,
  NULL,
  NULL,
  'a:2:{s:6:\"eng-GB\";s:10:\"Short name\";s:16:\"always-available\";s:6:\"eng-GB\";}',
  0
);
INSERT INTO ezcontentclass_attribute (
  can_translate,
  category,
  contentclass_id,
  data_float1,
  data_float2,
  data_float3,
  data_float4,
  data_int1,
  data_int2,
  data_int3,
  data_int4,
  data_text1,
  data_text2,
  data_text3,
  data_text4,
  data_text5,
  data_type_string,
  id,
  identifier,
  is_information_collector,
  is_required,
  is_searchable,
  placement,
  serialized_data_text,
  serialized_description_list,
  serialized_name_list,
  version
) VALUES (
  1,
  '',
  1,
  0,
  0,
  0,
  0,
  20,
  0,
  0,
  0,
  '',
  '',
  '',
  '',
  '',
  'ezxmltext',
  156,
  'description',
  0,
  0,
  1,
  4,
  NULL,
  NULL,
  'a:2:{s:6:\"eng-GB\";s:11:\"Description\";s:16:\"always-available\";s:6:\"eng-GB\";}',
  0
);
INSERT INTO ezcontentclass_attribute (
  can_translate,
  category,
  contentclass_id,
  data_float1,
  data_float2,
  data_float3,
  data_float4,
  data_int1,
  data_int2,
  data_int3,
  data_int4,
  data_text1,
  data_text2,
  data_text3,
  data_text4,
  data_text5,
  data_type_string,
  id,
  identifier,
  is_information_collector,
  is_required,
  is_searchable,
  placement,
  serialized_data_text,
  serialized_description_list,
  serialized_name_list,
  version
) VALUES (
  0,
  '',
  1,
  0,
  0,
  0,
  0,
  0,
  0,
  1,
  0,
  '',
  '',
  '',
  '',
  '',
  'ezboolean',
  158,
  'show_children',
  0,
  0,
  0,
  5,
  NULL,
  NULL,
  'a:2:{s:6:\"eng-GB\";s:13:\"Show children\";s:16:\"always-available\";s:6:\"eng-GB\";}',
  0
);
INSERT INTO ezcontentclass_attribute (
  can_translate,
  category,
  contentclass_id,
  data_float1,
  data_float2,
  data_float3,
  data_float4,
  data_int1,
  data_int2,
  data_int3,
  data_int4,
  data_text1,
  data_text2,
  data_text3,
  data_text4,
  data_text5,
  data_type_string,
  id,
  identifier,
  is_information_collector,
  is_required,
  is_searchable,
  placement,
  serialized_data_text,
  serialized_description_list,
  serialized_name_list,
  version
) VALUES (
  1,
  '',
  14,
  0,
  0,
  0,
  0,
  0,
  0,
  0,
  0,
  '',
  '',
  '',
  '',
  '',
  'ezstring',
  159,
  'name',
  0,
  0,
  1,
  1,
  NULL,
  NULL,
  'a:2:{s:6:\"eng-GB\";s:4:\"Name\";s:16:\"always-available\";s:6:\"eng-GB\";}',
  0
);
INSERT INTO ezcontentclass_attribute (
  can_translate,
  category,
  contentclass_id,
  data_float1,
  data_float2,
  data_float3,
  data_float4,
  data_int1,
  data_int2,
  data_int3,
  data_int4,
  data_text1,
  data_text2,
  data_text3,
  data_text4,
  data_text5,
  data_type_string,
  id,
  identifier,
  is_information_collector,
  is_required,
  is_searchable,
  placement,
  serialized_data_text,
  serialized_description_list,
  serialized_name_list,
  version
) VALUES (
  1,
  '',
  14,
  0,
  0,
  0,
  0,
  1,
  0,
  0,
  0,
  'site.ini',
  'SiteSettings',
  'IndexPage',
  '',
  'override;user;admin;demo',
  'ezinisetting',
  160,
  'indexpage',
  0,
  0,
  0,
  2,
  NULL,
  NULL,
  'a:2:{s:6:\"eng-GB\";s:10:\"Index Page\";s:16:\"always-available\";s:6:\"eng-GB\";}',
  0
);
INSERT INTO ezcontentclass_attribute (
  can_translate,
  category,
  contentclass_id,
  data_float1,
  data_float2,
  data_float3,
  data_float4,
  data_int1,
  data_int2,
  data_int3,
  data_int4,
  data_text1,
  data_text2,
  data_text3,
  data_text4,
  data_text5,
  data_type_string,
  id,
  identifier,
  is_information_collector,
  is_required,
  is_searchable,
  placement,
  serialized_data_text,
  serialized_description_list,
  serialized_name_list,
  version
) VALUES (
  1,
  '',
  14,
  0,
  0,
  0,
  0,
  1,
  0,
  0,
  0,
  'site.ini',
  'SiteSettings',
  'DefaultPage',
  '',
  'override;user;admin;demo',
  'ezinisetting',
  161,
  'defaultpage',
  0,
  0,
  0,
  3,
  NULL,
  NULL,
  'a:2:{s:6:\"eng-GB\";s:12:\"Default Page\";s:16:\"always-available\";s:6:\"eng-GB\";}',
  0
);
INSERT INTO ezcontentclass_attribute (
  can_translate,
  category,
  contentclass_id,
  data_float1,
  data_float2,
  data_float3,
  data_float4,
  data_int1,
  data_int2,
  data_int3,
  data_int4,
  data_text1,
  data_text2,
  data_text3,
  data_text4,
  data_text5,
  data_type_string,
  id,
  identifier,
  is_information_collector,
  is_required,
  is_searchable,
  placement,
  serialized_data_text,
  serialized_description_list,
  serialized_name_list,
  version
) VALUES (
  1,
  '',
  14,
  0,
  0,
  0,
  0,
  2,
  0,
  0,
  0,
  'site.ini',
  'DebugSettings',
  'DebugOutput',
  '',
  'override;user;admin;demo',
  'ezinisetting',
  162,
  'debugoutput',
  0,
  0,
  0,
  4,
  NULL,
  NULL,
  'a:2:{s:6:\"eng-GB\";s:12:\"Debug Output\";s:16:\"always-available\";s:6:\"eng-GB\";}',
  0
);
INSERT INTO ezcontentclass_attribute (
  can_translate,
  category,
  contentclass_id,
  data_float1,
  data_float2,
  data_float3,
  data_float4,
  data_int1,
  data_int2,
  data_int3,
  data_int4,
  data_text1,
  data_text2,
  data_text3,
  data_text4,
  data_text5,
  data_type_string,
  id,
  identifier,
  is_information_collector,
  is_required,
  is_searchable,
  placement,
  serialized_data_text,
  serialized_description_list,
  serialized_name_list,
  version
) VALUES (
  1,
  '',
  14,
  0,
  0,
  0,
  0,
  2,
  0,
  0,
  0,
  'site.ini',
  'DebugSettings',
  'DebugByIP',
  '',
  'override;user;admin;demo',
  'ezinisetting',
  163,
  'debugbyip',
  0,
  0,
  0,
  5,
  NULL,
  NULL,
  'a:2:{s:6:\"eng-GB\";s:11:\"Debug By IP\";s:16:\"always-available\";s:6:\"eng-GB\";}',
  0
);
INSERT INTO ezcontentclass_attribute (
  can_translate,
  category,
  contentclass_id,
  data_float1,
  data_float2,
  data_float3,
  data_float4,
  data_int1,
  data_int2,
  data_int3,
  data_int4,
  data_text1,
  data_text2,
  data_text3,
  data_text4,
  data_text5,
  data_type_string,
  id,
  identifier,
  is_information_collector,
  is_required,
  is_searchable,
  placement,
  serialized_data_text,
  serialized_description_list,
  serialized_name_list,
  version
) VALUES (
  1,
  '',
  14,
  0,
  0,
  0,
  0,
  6,
  0,
  0,
  0,
  'site.ini',
  'DebugSettings',
  'DebugIPList',
  '',
  'override;user;admin;demo',
  'ezinisetting',
  164,
  'debugiplist',
  0,
  0,
  0,
  6,
  NULL,
  NULL,
  'a:2:{s:6:\"eng-GB\";s:13:\"Debug IP List\";s:16:\"always-available\";s:6:\"eng-GB\";}',
  0
);
INSERT INTO ezcontentclass_attribute (
  can_translate,
  category,
  contentclass_id,
  data_float1,
  data_float2,
  data_float3,
  data_float4,
  data_int1,
  data_int2,
  data_int3,
  data_int4,
  data_text1,
  data_text2,
  data_text3,
  data_text4,
  data_text5,
  data_type_string,
  id,
  identifier,
  is_information_collector,
  is_required,
  is_searchable,
  placement,
  serialized_data_text,
  serialized_description_list,
  serialized_name_list,
  version
) VALUES (
  1,
  '',
  14,
  0,
  0,
  0,
  0,
  2,
  0,
  0,
  0,
  'site.ini',
  'DebugSettings',
  'DebugRedirection',
  '',
  'override;user;admin;demo',
  'ezinisetting',
  165,
  'debugredirection',
  0,
  0,
  0,
  7,
  NULL,
  NULL,
  'a:2:{s:6:\"eng-GB\";s:17:\"Debug Redirection\";s:16:\"always-available\";s:6:\"eng-GB\";}',
  0
);
INSERT INTO ezcontentclass_attribute (
  can_translate,
  category,
  contentclass_id,
  data_float1,
  data_float2,
  data_float3,
  data_float4,
  data_int1,
  data_int2,
  data_int3,
  data_int4,
  data_text1,
  data_text2,
  data_text3,
  data_text4,
  data_text5,
  data_type_string,
  id,
  identifier,
  is_information_collector,
  is_required,
  is_searchable,
  placement,
  serialized_data_text,
  serialized_description_list,
  serialized_name_list,
  version
) VALUES (
  1,
  '',
  14,
  0,
  0,
  0,
  0,
  2,
  0,
  0,
  0,
  'site.ini',
  'ContentSettings',
  'ViewCaching',
  '',
  'override;user;admin;demo',
  'ezinisetting',
  166,
  'viewcaching',
  0,
  0,
  0,
  8,
  NULL,
  NULL,
  'a:2:{s:6:\"eng-GB\";s:12:\"View Caching\";s:16:\"always-available\";s:6:\"eng-GB\";}',
  0
);
INSERT INTO ezcontentclass_attribute (
  can_translate,
  category,
  contentclass_id,
  data_float1,
  data_float2,
  data_float3,
  data_float4,
  data_int1,
  data_int2,
  data_int3,
  data_int4,
  data_text1,
  data_text2,
  data_text3,
  data_text4,
  data_text5,
  data_type_string,
  id,
  identifier,
  is_information_collector,
  is_required,
  is_searchable,
  placement,
  serialized_data_text,
  serialized_description_list,
  serialized_name_list,
  version
) VALUES (
  1,
  '',
  14,
  0,
  0,
  0,
  0,
  2,
  0,
  0,
  0,
  'site.ini',
  'TemplateSettings',
  'TemplateCache',
  '',
  'override;user;admin;demo',
  'ezinisetting',
  167,
  'templatecache',
  0,
  0,
  0,
  9,
  NULL,
  NULL,
  'a:2:{s:6:\"eng-GB\";s:14:\"Template Cache\";s:16:\"always-available\";s:6:\"eng-GB\";}',
  0
);
INSERT INTO ezcontentclass_attribute (
  can_translate,
  category,
  contentclass_id,
  data_float1,
  data_float2,
  data_float3,
  data_float4,
  data_int1,
  data_int2,
  data_int3,
  data_int4,
  data_text1,
  data_text2,
  data_text3,
  data_text4,
  data_text5,
  data_type_string,
  id,
  identifier,
  is_information_collector,
  is_required,
  is_searchable,
  placement,
  serialized_data_text,
  serialized_description_list,
  serialized_name_list,
  version
) VALUES (
  1,
  '',
  14,
  0,
  0,
  0,
  0,
  2,
  0,
  0,
  0,
  'site.ini',
  'TemplateSettings',
  'TemplateCompile',
  '',
  'override;user;admin;demo',
  'ezinisetting',
  168,
  'templatecompile',
  0,
  0,
  0,
  10,
  NULL,
  NULL,
  'a:2:{s:6:\"eng-GB\";s:16:\"Template Compile\";s:16:\"always-available\";s:6:\"eng-GB\";}',
  0
);
INSERT INTO ezcontentclass_attribute (
  can_translate,
  category,
  contentclass_id,
  data_float1,
  data_float2,
  data_float3,
  data_float4,
  data_int1,
  data_int2,
  data_int3,
  data_int4,
  data_text1,
  data_text2,
  data_text3,
  data_text4,
  data_text5,
  data_type_string,
  id,
  identifier,
  is_information_collector,
  is_required,
  is_searchable,
  placement,
  serialized_data_text,
  serialized_description_list,
  serialized_name_list,
  version
) VALUES (
  1,
  '',
  14,
  0,
  0,
  0,
  0,
  6,
  0,
  0,
  0,
  'image.ini',
  'small',
  'Filters',
  '',
  'override;user;admin;demo',
  'ezinisetting',
  169,
  'imagesmall',
  0,
  0,
  0,
  11,
  NULL,
  NULL,
  'a:2:{s:6:\"eng-GB\";s:16:\"Image Small Size\";s:16:\"always-available\";s:6:\"eng-GB\";}',
  0
);
INSERT INTO ezcontentclass_attribute (
  can_translate,
  category,
  contentclass_id,
  data_float1,
  data_float2,
  data_float3,
  data_float4,
  data_int1,
  data_int2,
  data_int3,
  data_int4,
  data_text1,
  data_text2,
  data_text3,
  data_text4,
  data_text5,
  data_type_string,
  id,
  identifier,
  is_information_collector,
  is_required,
  is_searchable,
  placement,
  serialized_data_text,
  serialized_description_list,
  serialized_name_list,
  version
) VALUES (
  1,
  '',
  14,
  0,
  0,
  0,
  0,
  6,
  0,
  0,
  0,
  'image.ini',
  'medium',
  'Filters',
  '',
  'override;user;admin;demo',
  'ezinisetting',
  170,
  'imagemedium',
  0,
  0,
  0,
  12,
  NULL,
  NULL,
  'a:2:{s:6:\"eng-GB\";s:17:\"Image Medium Size\";s:16:\"always-available\";s:6:\"eng-GB\";}',
  0
);
INSERT INTO ezcontentclass_attribute (
  can_translate,
  category,
  contentclass_id,
  data_float1,
  data_float2,
  data_float3,
  data_float4,
  data_int1,
  data_int2,
  data_int3,
  data_int4,
  data_text1,
  data_text2,
  data_text3,
  data_text4,
  data_text5,
  data_type_string,
  id,
  identifier,
  is_information_collector,
  is_required,
  is_searchable,
  placement,
  serialized_data_text,
  serialized_description_list,
  serialized_name_list,
  version
) VALUES (
  1,
  '',
  14,
  0,
  0,
  0,
  0,
  6,
  0,
  0,
  0,
  'image.ini',
  'large',
  'Filters',
  '',
  'override;user;admin;demo',
  'ezinisetting',
  171,
  'imagelarge',
  0,
  0,
  0,
  13,
  NULL,
  NULL,
  'a:2:{s:6:\"eng-GB\";s:16:\"Image Large Size\";s:16:\"always-available\";s:6:\"eng-GB\";}',
  0
);
INSERT INTO ezcontentclass_attribute (
  can_translate,
  category,
  contentclass_id,
  data_float1,
  data_float2,
  data_float3,
  data_float4,
  data_int1,
  data_int2,
  data_int3,
  data_int4,
  data_text1,
  data_text2,
  data_text3,
  data_text4,
  data_text5,
  data_type_string,
  id,
  identifier,
  is_information_collector,
  is_required,
  is_searchable,
  placement,
  serialized_data_text,
  serialized_description_list,
  serialized_name_list,
  version
) VALUES (
  1,
  '',
  15,
  0,
  0,
  0,
  0,
  1,
  0,
  0,
  0,
  'site.ini',
  'SiteSettings',
  'SiteName',
  '',
  'override;user;admin;demo',
  'ezinisetting',
  172,
  'title',
  0,
  0,
  0,
  1,
  NULL,
  NULL,
  'a:2:{s:6:\"eng-GB\";s:5:\"Title\";s:16:\"always-available\";s:6:\"eng-GB\";}',
  0
);
INSERT INTO ezcontentclass_attribute (
  can_translate,
  category,
  contentclass_id,
  data_float1,
  data_float2,
  data_float3,
  data_float4,
  data_int1,
  data_int2,
  data_int3,
  data_int4,
  data_text1,
  data_text2,
  data_text3,
  data_text4,
  data_text5,
  data_type_string,
  id,
  identifier,
  is_information_collector,
  is_required,
  is_searchable,
  placement,
  serialized_data_text,
  serialized_description_list,
  serialized_name_list,
  version
) VALUES (
  1,
  '',
  15,
  0,
  0,
  0,
  0,
  6,
  0,
  0,
  0,
  'site.ini',
  'SiteSettings',
  'MetaDataArray',
  '',
  'override;user;admin;demo',
  'ezinisetting',
  173,
  'meta_data',
  0,
  0,
  0,
  2,
  NULL,
  NULL,
  'a:2:{s:6:\"eng-GB\";s:9:\"Meta data\";s:16:\"always-available\";s:6:\"eng-GB\";}',
  0
);
INSERT INTO ezcontentclass_attribute (
  can_translate,
  category,
  contentclass_id,
  data_float1,
  data_float2,
  data_float3,
  data_float4,
  data_int1,
  data_int2,
  data_int3,
  data_int4,
  data_text1,
  data_text2,
  data_text3,
  data_text4,
  data_text5,
  data_type_string,
  id,
  identifier,
  is_information_collector,
  is_required,
  is_searchable,
  placement,
  serialized_data_text,
  serialized_description_list,
  serialized_name_list,
  version
) VALUES (
  1,
  '',
  15,
  0,
  0,
  0,
  0,
  0,
  0,
  0,
  0,
  '',
  '',
  '',
  '',
  '',
  'ezimage',
  174,
  'image',
  0,
  0,
  0,
  3,
  NULL,
  NULL,
  'a:2:{s:6:\"eng-GB\";s:5:\"Image\";s:16:\"always-available\";s:6:\"eng-GB\";}',
  0
);
INSERT INTO ezcontentclass_attribute (
  can_translate,
  category,
  contentclass_id,
  data_float1,
  data_float2,
  data_float3,
  data_float4,
  data_int1,
  data_int2,
  data_int3,
  data_int4,
  data_text1,
  data_text2,
  data_text3,
  data_text4,
  data_text5,
  data_type_string,
  id,
  identifier,
  is_information_collector,
  is_required,
  is_searchable,
  placement,
  serialized_data_text,
  serialized_description_list,
  serialized_name_list,
  version
) VALUES (
  1,
  '',
  15,
  0,
  0,
  0,
  0,
  0,
  0,
  0,
  0,
  'sitestyle',
  '',
  '',
  '',
  '',
  'ezpackage',
  175,
  'sitestyle',
  0,
  0,
  0,
  4,
  NULL,
  NULL,
  'a:2:{s:6:\"eng-GB\";s:9:\"Sitestyle\";s:16:\"always-available\";s:6:\"eng-GB\";}',
  0
);
INSERT INTO ezcontentclass_attribute (
  can_translate,
  category,
  contentclass_id,
  data_float1,
  data_float2,
  data_float3,
  data_float4,
  data_int1,
  data_int2,
  data_int3,
  data_int4,
  data_text1,
  data_text2,
  data_text3,
  data_text4,
  data_text5,
  data_type_string,
  id,
  identifier,
  is_information_collector,
  is_required,
  is_searchable,
  placement,
  serialized_data_text,
  serialized_description_list,
  serialized_name_list,
  version
) VALUES (
  1,
  '',
  15,
  0,
  0,
  0,
  0,
  0,
  0,
  0,
  0,
  '',
  '',
  '',
  '',
  '',
  'ezstring',
  176,
  'id',
  0,
  0,
  1,
  5,
  NULL,
  NULL,
  'a:2:{s:6:\"eng-GB\";s:2:\"id\";s:16:\"always-available\";s:6:\"eng-GB\";}',
  0
);
INSERT INTO ezcontentclass_attribute (
  can_translate,
  category,
  contentclass_id,
  data_float1,
  data_float2,
  data_float3,
  data_float4,
  data_int1,
  data_int2,
  data_int3,
  data_int4,
  data_text1,
  data_text2,
  data_text3,
  data_text4,
  data_text5,
  data_type_string,
  id,
  identifier,
  is_information_collector,
  is_required,
  is_searchable,
  placement,
  serialized_data_text,
  serialized_description_list,
  serialized_name_list,
  version
) VALUES (
  1,
  '',
  15,
  0,
  0,
  0,
  0,
  1,
  0,
  0,
  0,
  'site.ini',
  'MailSettings',
  'AdminEmail',
  '',
  'override;user;admin;demo',
  'ezinisetting',
  177,
  'email',
  0,
  0,
  0,
  6,
  NULL,
  NULL,
  'a:2:{s:6:\"eng-GB\";s:5:\"Email\";s:16:\"always-available\";s:6:\"eng-GB\";}',
  0
);
INSERT INTO ezcontentclass_attribute (
  can_translate,
  category,
  contentclass_id,
  data_float1,
  data_float2,
  data_float3,
  data_float4,
  data_int1,
  data_int2,
  data_int3,
  data_int4,
  data_text1,
  data_text2,
  data_text3,
  data_text4,
  data_text5,
  data_type_string,
  id,
  identifier,
  is_information_collector,
  is_required,
  is_searchable,
  placement,
  serialized_data_text,
  serialized_description_list,
  serialized_name_list,
  version
) VALUES (
  1,
  '',
  15,
  0,
  0,
  0,
  0,
  1,
  0,
  0,
  0,
  'site.ini',
  'SiteSettings',
  'SiteURL',
  '',
  'override;user;admin;demo',
  'ezinisetting',
  178,
  'siteurl',
  0,
  0,
  0,
  7,
  NULL,
  NULL,
  'a:2:{s:6:\"eng-GB\";s:8:\"Site URL\";s:16:\"always-available\";s:6:\"eng-GB\";}',
  0
);
INSERT INTO ezcontentclass_attribute (
  can_translate,
  category,
  contentclass_id,
  data_float1,
  data_float2,
  data_float3,
  data_float4,
  data_int1,
  data_int2,
  data_int3,
  data_int4,
  data_text1,
  data_text2,
  data_text3,
  data_text4,
  data_text5,
  data_type_string,
  id,
  identifier,
  is_information_collector,
  is_required,
  is_searchable,
  placement,
  serialized_data_text,
  serialized_description_list,
  serialized_name_list,
  version
) VALUES (
  1,
  '',
  4,
  0,
  0,
  0,
  0,
  10,
  0,
  0,
  0,
  '',
  '',
  '',
  '',
  '',
  'eztext',
  179,
  'signature',
  0,
  0,
  1,
  4,
  NULL,
  NULL,
  'a:2:{s:6:\"eng-GB\";s:9:\"Signature\";s:16:\"always-available\";s:6:\"eng-GB\";}',
  0
);
INSERT INTO ezcontentclass_attribute (
  can_translate,
  category,
  contentclass_id,
  data_float1,
  data_float2,
  data_float3,
  data_float4,
  data_int1,
  data_int2,
  data_int3,
  data_int4,
  data_text1,
  data_text2,
  data_text3,
  data_text4,
  data_text5,
  data_type_string,
  id,
  identifier,
  is_information_collector,
  is_required,
  is_searchable,
  placement,
  serialized_data_text,
  serialized_description_list,
  serialized_name_list,
  version
) VALUES (
  1,
  '',
  4,
  0,
  0,
  0,
  0,
  1,
  0,
  0,
  0,
  '',
  '',
  '',
  '',
  '',
  'ezimage',
  180,
  'image',
  0,
  0,
  0,
  5,
  NULL,
  NULL,
  'a:2:{s:6:\"eng-GB\";s:5:\"Image\";s:16:\"always-available\";s:6:\"eng-GB\";}',
  0
);

INSERT INTO ezcontentclass_classgroup (
  contentclass_id,
  contentclass_version,
  group_id,
  group_name
) VALUES (
  1,
  0,
  1,
  'Content'
);
INSERT INTO ezcontentclass_classgroup (
  contentclass_id,
  contentclass_version,
  group_id,
  group_name
) VALUES (
  2,
  0,
  1,
  'Content'
);
INSERT INTO ezcontentclass_classgroup (
  contentclass_id,
  contentclass_version,
  group_id,
  group_name
) VALUES (
  3,
  0,
  2,
  'Users'
);
INSERT INTO ezcontentclass_classgroup (
  contentclass_id,
  contentclass_version,
  group_id,
  group_name
) VALUES (
  4,
  0,
  2,
  'Users'
);
INSERT INTO ezcontentclass_classgroup (
  contentclass_id,
  contentclass_version,
  group_id,
  group_name
) VALUES (
  5,
  0,
  3,
  'Media'
);
INSERT INTO ezcontentclass_classgroup (
  contentclass_id,
  contentclass_version,
  group_id,
  group_name
) VALUES (
  11,
  0,
  1,
  'Content'
);
INSERT INTO ezcontentclass_classgroup (
  contentclass_id,
  contentclass_version,
  group_id,
  group_name
) VALUES (
  12,
  0,
  3,
  'Media'
);
INSERT INTO ezcontentclass_classgroup (
  contentclass_id,
  contentclass_version,
  group_id,
  group_name
) VALUES (
  13,
  0,
  1,
  'Content'
);
INSERT INTO ezcontentclass_classgroup (
  contentclass_id,
  contentclass_version,
  group_id,
  group_name
) VALUES (
  14,
  0,
  4,
  'Setup'
);
INSERT INTO ezcontentclass_classgroup (
  contentclass_id,
  contentclass_version,
  group_id,
  group_name
) VALUES (
  15,
  0,
  4,
  'Setup'
);

INSERT INTO ezcontentclass_name (
  contentclass_id,
  contentclass_version,
  language_id,
  language_locale,
  name
) VALUES (
  1,
  0,
  3,
  'eng-GB',
  'Folder'
);
INSERT INTO ezcontentclass_name (
  contentclass_id,
  contentclass_version,
  language_id,
  language_locale,
  name
) VALUES (
  2,
  0,
  3,
  'eng-GB',
  'Article'
);
INSERT INTO ezcontentclass_name (
  contentclass_id,
  contentclass_version,
  language_id,
  language_locale,
  name
) VALUES (
  3,
  0,
  3,
  'eng-GB',
  'User group'
);
INSERT INTO ezcontentclass_name (
  contentclass_id,
  contentclass_version,
  language_id,
  language_locale,
  name
) VALUES (
  4,
  0,
  3,
  'eng-GB',
  'User'
);
INSERT INTO ezcontentclass_name (
  contentclass_id,
  contentclass_version,
  language_id,
  language_locale,
  name
) VALUES (
  5,
  0,
  3,
  'eng-GB',
  'Image'
);
INSERT INTO ezcontentclass_name (
  contentclass_id,
  contentclass_version,
  language_id,
  language_locale,
  name
) VALUES (
  11,
  0,
  3,
  'eng-GB',
  'Link'
);
INSERT INTO ezcontentclass_name (
  contentclass_id,
  contentclass_version,
  language_id,
  language_locale,
  name
) VALUES (
  12,
  0,
  3,
  'eng-GB',
  'File'
);
INSERT INTO ezcontentclass_name (
  contentclass_id,
  contentclass_version,
  language_id,
  language_locale,
  name
) VALUES (
  13,
  0,
  3,
  'eng-GB',
  'Comment'
);
INSERT INTO ezcontentclass_name (
  contentclass_id,
  contentclass_version,
  language_id,
  language_locale,
  name
) VALUES (
  14,
  0,
  3,
  'eng-GB',
  'Common ini settings'
);
INSERT INTO ezcontentclass_name (
  contentclass_id,
  contentclass_version,
  language_id,
  language_locale,
  name
) VALUES (
  15,
  0,
  3,
  'eng-GB',
  'Template look'
);

INSERT INTO ezcontentclassgroup (
  created,
  creator_id,
  id,
  modified,
  modifier_id,
  name
) VALUES (
  1031216928,
  14,
  1,
  1033922106,
  14,
  'Content'
);
INSERT INTO ezcontentclassgroup (
  created,
  creator_id,
  id,
  modified,
  modifier_id,
  name
) VALUES (
  1031216941,
  14,
  2,
  1033922113,
  14,
  'Users'
);
INSERT INTO ezcontentclassgroup (
  created,
  creator_id,
  id,
  modified,
  modifier_id,
  name
) VALUES (
  1032009743,
  14,
  3,
  1033922120,
  14,
  'Media'
);
INSERT INTO ezcontentclassgroup (
  created,
  creator_id,
  id,
  modified,
  modifier_id,
  name
) VALUES (
  1081858024,
  14,
  4,
  1081858024,
  14,
  'Setup'
);

INSERT INTO ezcontentobject (
  contentclass_id,
  current_version,
  id,
  initial_language_id,
  language_mask,
  modified,
  name,
  owner_id,
  published,
  remote_id,
  section_id,
  status
) VALUES (
  1,
  6,
  1,
  2,
  3,
  1301073466,
  'eZ Publish',
  14,
  1033917596,
  '9459d3c29e15006e45197295722c7ade',
  1,
  1
);
INSERT INTO ezcontentobject (
  contentclass_id,
  current_version,
  id,
  initial_language_id,
  language_mask,
  modified,
  name,
  owner_id,
  published,
  remote_id,
  section_id,
  status
) VALUES (
  3,
  1,
  4,
  2,
  3,
  1033917596,
  'Users',
  14,
  1033917596,
  'f5c88a2209584891056f987fd965b0ba',
  2,
  1
);
INSERT INTO ezcontentobject (
  contentclass_id,
  current_version,
  id,
  initial_language_id,
  language_mask,
  modified,
  name,
  owner_id,
  published,
  remote_id,
  section_id,
  status
) VALUES (
  4,
  2,
  10,
  2,
  3,
  1072180405,
  'Anonymous User',
  14,
  1033920665,
  'faaeb9be3bd98ed09f606fc16d144eca',
  2,
  1
);
INSERT INTO ezcontentobject (
  contentclass_id,
  current_version,
  id,
  initial_language_id,
  language_mask,
  modified,
  name,
  owner_id,
  published,
  remote_id,
  section_id,
  status
) VALUES (
  3,
  1,
  11,
  2,
  3,
  1033920746,
  'Guest accounts',
  14,
  1033920746,
  '5f7f0bdb3381d6a461d8c29ff53d908f',
  2,
  1
);
INSERT INTO ezcontentobject (
  contentclass_id,
  current_version,
  id,
  initial_language_id,
  language_mask,
  modified,
  name,
  owner_id,
  published,
  remote_id,
  section_id,
  status
) VALUES (
  3,
  1,
  12,
  2,
  3,
  1033920775,
  'Administrator users',
  14,
  1033920775,
  '9b47a45624b023b1a76c73b74d704acf',
  2,
  1
);
INSERT INTO ezcontentobject (
  contentclass_id,
  current_version,
  id,
  initial_language_id,
  language_mask,
  modified,
  name,
  owner_id,
  published,
  remote_id,
  section_id,
  status
) VALUES (
  3,
  1,
  13,
  2,
  3,
  1033920794,
  'Editors',
  14,
  1033920794,
  '3c160cca19fb135f83bd02d911f04db2',
  2,
  1
);
INSERT INTO ezcontentobject (
  contentclass_id,
  current_version,
  id,
  initial_language_id,
  language_mask,
  modified,
  name,
  owner_id,
  published,
  remote_id,
  section_id,
  status
) VALUES (
  4,
  3,
  14,
  2,
  3,
  1301062024,
  'Administrator User',
  14,
  1033920830,
  '1bb4fe25487f05527efa8bfd394cecc7',
  2,
  1
);
INSERT INTO ezcontentobject (
  contentclass_id,
  current_version,
  id,
  initial_language_id,
  language_mask,
  modified,
  name,
  owner_id,
  published,
  remote_id,
  section_id,
  status
) VALUES (
  1,
  1,
  41,
  2,
  3,
  1060695457,
  'Media',
  14,
  1060695457,
  'a6e35cbcb7cd6ae4b691f3eee30cd262',
  3,
  1
);
INSERT INTO ezcontentobject (
  contentclass_id,
  current_version,
  id,
  initial_language_id,
  language_mask,
  modified,
  name,
  owner_id,
  published,
  remote_id,
  section_id,
  status
) VALUES (
  3,
  1,
  42,
  2,
  3,
  1072180330,
  'Anonymous Users',
  14,
  1072180330,
  '15b256dbea2ae72418ff5facc999e8f9',
  2,
  1
);
INSERT INTO ezcontentobject (
  contentclass_id,
  current_version,
  id,
  initial_language_id,
  language_mask,
  modified,
  name,
  owner_id,
  published,
  remote_id,
  section_id,
  status
) VALUES (
  1,
  1,
  45,
  2,
  3,
  1079684190,
  'Setup',
  14,
  1079684190,
  '241d538ce310074e602f29f49e44e938',
  4,
  1
);
INSERT INTO ezcontentobject (
  contentclass_id,
  current_version,
  id,
  initial_language_id,
  language_mask,
  modified,
  name,
  owner_id,
  published,
  remote_id,
  section_id,
  status
) VALUES (
  1,
  1,
  49,
  2,
  3,
  1080220197,
  'Images',
  14,
  1080220197,
  'e7ff633c6b8e0fd3531e74c6e712bead',
  3,
  1
);
INSERT INTO ezcontentobject (
  contentclass_id,
  current_version,
  id,
  initial_language_id,
  language_mask,
  modified,
  name,
  owner_id,
  published,
  remote_id,
  section_id,
  status
) VALUES (
  1,
  1,
  50,
  2,
  3,
  1080220220,
  'Files',
  14,
  1080220220,
  '732a5acd01b51a6fe6eab448ad4138a9',
  3,
  1
);
INSERT INTO ezcontentobject (
  contentclass_id,
  current_version,
  id,
  initial_language_id,
  language_mask,
  modified,
  name,
  owner_id,
  published,
  remote_id,
  section_id,
  status
) VALUES (
  1,
  1,
  51,
  2,
  3,
  1080220233,
  'Multimedia',
  14,
  1080220233,
  '09082deb98662a104f325aaa8c4933d3',
  3,
  1
);
INSERT INTO ezcontentobject (
  contentclass_id,
  current_version,
  id,
  initial_language_id,
  language_mask,
  modified,
  name,
  owner_id,
  published,
  remote_id,
  section_id,
  status
) VALUES (
  14,
  1,
  52,
  2,
  2,
  1082016591,
  'Common INI settings',
  14,
  1082016591,
  '27437f3547db19cf81a33c92578b2c89',
  4,
  1
);
INSERT INTO ezcontentobject (
  contentclass_id,
  current_version,
  id,
  initial_language_id,
  language_mask,
  modified,
  name,
  owner_id,
  published,
  remote_id,
  section_id,
  status
) VALUES (
  15,
  2,
  54,
  2,
  2,
  1301062376,
  'Plain site',
  14,
  1082016652,
  '8b8b22fe3c6061ed500fbd2b377b885f',
  5,
  1
);
INSERT INTO ezcontentobject (
  contentclass_id,
  current_version,
  id,
  initial_language_id,
  language_mask,
  modified,
  name,
  owner_id,
  published,
  remote_id,
  section_id,
  status
) VALUES (
  1,
  1,
  56,
  2,
  3,
  1103023132,
  'Design',
  14,
  1103023132,
  '08799e609893f7aba22f10cb466d9cc8',
  5,
  1
);

INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  4,
  1,
  0,
  0,
  'Welcome to eZ Publish',
  'ezstring',
  1,
  'eng-GB',
  3,
  0,
  'welcome to ez publish',
  6
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  119,
  1,
  0,
  1045487555,
  '<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\" xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\" xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\"><paragraph xmlns:tmp=\"http://ez.no/namespaces/ezpublish3/temporary/\">This is eZ plain site package with a limited setup of the eZ Publish functionality. For a full blown eZ Publish please chose the Website Interface or the eZ Flow site package at the installation.</paragraph></section>\n',
  'ezxmltext',
  2,
  'eng-GB',
  3,
  0,
  '',
  6
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  7,
  4,
  NULL,
  NULL,
  'Main group',
  'ezstring',
  7,
  'eng-GB',
  3,
  0,
  '',
  1
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  6,
  4,
  NULL,
  NULL,
  'Users',
  'ezstring',
  8,
  'eng-GB',
  3,
  0,
  '',
  1
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  8,
  10,
  0,
  0,
  'Anonymous',
  'ezstring',
  19,
  'eng-GB',
  3,
  0,
  'anonymous',
  2
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  9,
  10,
  0,
  0,
  'User',
  'ezstring',
  20,
  'eng-GB',
  3,
  0,
  'user',
  2
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  12,
  10,
  0,
  0,
  '',
  'ezuser',
  21,
  'eng-GB',
  3,
  0,
  '',
  2
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  6,
  11,
  0,
  0,
  'Guest accounts',
  'ezstring',
  22,
  'eng-GB',
  3,
  0,
  '',
  1
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  7,
  11,
  0,
  0,
  '',
  'ezstring',
  23,
  'eng-GB',
  3,
  0,
  '',
  1
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  6,
  12,
  0,
  0,
  'Administrator users',
  'ezstring',
  24,
  'eng-GB',
  3,
  0,
  '',
  1
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  7,
  12,
  0,
  0,
  '',
  'ezstring',
  25,
  'eng-GB',
  3,
  0,
  '',
  1
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  6,
  13,
  0,
  0,
  'Editors',
  'ezstring',
  26,
  'eng-GB',
  3,
  0,
  '',
  1
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  7,
  13,
  0,
  0,
  '',
  'ezstring',
  27,
  'eng-GB',
  3,
  0,
  '',
  1
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  8,
  14,
  0,
  0,
  'Administrator',
  'ezstring',
  28,
  'eng-GB',
  3,
  0,
  'administrator',
  3
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  9,
  14,
  0,
  0,
  'User',
  'ezstring',
  29,
  'eng-GB',
  3,
  0,
  'user',
  3
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  30,
  12,
  14,
  0,
  0,
  '',
  'ezuser',
  30,
  'eng-GB',
  3,
  0,
  '',
  3
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  4,
  41,
  0,
  0,
  'Media',
  'ezstring',
  98,
  'eng-GB',
  3,
  0,
  '',
  1
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  119,
  41,
  0,
  1045487555,
  '<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',
  'ezxmltext',
  99,
  'eng-GB',
  3,
  0,
  '',
  1
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  6,
  42,
  0,
  0,
  'Anonymous Users',
  'ezstring',
  100,
  'eng-GB',
  3,
  0,
  'anonymous users',
  1
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  7,
  42,
  0,
  0,
  'User group for the anonymous user',
  'ezstring',
  101,
  'eng-GB',
  3,
  0,
  'user group for the anonymous user',
  1
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  155,
  1,
  0,
  0,
  'eZ Publish',
  'ezstring',
  102,
  'eng-GB',
  3,
  0,
  'ez publish',
  6
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  155,
  41,
  0,
  0,
  '',
  'ezstring',
  103,
  'eng-GB',
  3,
  0,
  '',
  1
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  156,
  1,
  0,
  1045487555,
  '<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\" xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\" xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\"><paragraph xmlns:tmp=\"http://ez.no/namespaces/ezpublish3/temporary/\">eZ Publish is a popular open source content management system and development framework. It allows the development of professional, customized and dynamic web solutions. It can be used to build anything from a personal homepage to a multinational corporate website with role based multiuser access, online shopping, discussion forums and other advanced functionality. In addition, because of its open nature, eZ Publish can easily be plugged into, communicate and coexist with existing IT-solutions.</paragraph><section><header>Documentation and guidance</header><paragraph xmlns:tmp=\"http://ez.no/namespaces/ezpublish3/temporary/\">The <link target=\"_blank\" url_id=\"9\">eZ Publish documentation</link> covers common topics related to the setup and daily use of the eZ Publish content management system/framework. In addition, it also covers some advanced topics. People who are unfamiliar with eZ Publish should at least read the \"eZ Publish basics\" chapter.</paragraph><paragraph xmlns:tmp=\"http://ez.no/namespaces/ezpublish3/temporary/\">If you\'re unable to find an answer/solution to a specific question/problem within the documentation pages, you should make use of the official <link target=\"_blank\" url_id=\"4\">eZ Publish forum</link>. People who need professional help should purchase <link target=\"_blank\" url_id=\"10\">support</link> or <link target=\"_blank\" url_id=\"11\">consulting</link> services. It is also possible to sign up for various <link target=\"_blank\" url_id=\"12\">training sessions</link>.</paragraph><paragraph xmlns:tmp=\"http://ez.no/namespaces/ezpublish3/temporary/\">For more information about eZ Publish and other products/services from eZ Systems, please visit <link target=\"_blank\" url_id=\"8\">ez.no</link>.</paragraph></section><section><header>Tutorials</header><section><header><strong>New users</strong></header><paragraph xmlns:tmp=\"http://ez.no/namespaces/ezpublish3/temporary/\"><ul><li><paragraph xmlns:tmp=\"http://ez.no/namespaces/ezpublish3/temporary/\"><link target=\"_blank\" xhtml:id=\"internal-source-marker_0.15448186383582652\" url_id=\"13\">eZ Publish Administration Interface</link></paragraph></li><li><paragraph xmlns:tmp=\"http://ez.no/namespaces/ezpublish3/temporary/\"><link target=\"_blank\" url_id=\"14\">eZ Publish Online Editor Video</link></paragraph></li><li><paragraph xmlns:tmp=\"http://ez.no/namespaces/ezpublish3/temporary/\"><link target=\"_blank\" xhtml:id=\"internal-source-marker_0.15448186383582652\" url_id=\"15\">eZ Flow Video Tutorial</link></paragraph></li></ul></paragraph></section><section><header>Experienced users</header><paragraph xmlns:tmp=\"http://ez.no/namespaces/ezpublish3/temporary/\"><ul><li><paragraph xmlns:tmp=\"http://ez.no/namespaces/ezpublish3/temporary/\"><link target=\"_blank\" url_id=\"16\">How to develop eZ Publish Extensions</link></paragraph></li><li><paragraph xmlns:tmp=\"http://ez.no/namespaces/ezpublish3/temporary/\"><link target=\"_blank\" xhtml:id=\"internal-source-marker_0.15448186383582652\" url_id=\"17\">How to create custom workflow</link></paragraph></li><li><paragraph xmlns:tmp=\"http://ez.no/namespaces/ezpublish3/temporary/\"><link target=\"_blank\" url_id=\"18\">How to use REST API interface</link></paragraph></li><li><paragraph xmlns:tmp=\"http://ez.no/namespaces/ezpublish3/temporary/\"><link target=\"_blank\" url_id=\"19\">Asynchronous publishing</link></paragraph></li><li><paragraph xmlns:tmp=\"http://ez.no/namespaces/ezpublish3/temporary/\"><link target=\"_blank\" xhtml:id=\"internal-source-marker_0.15448186383582652\" url_id=\"20\">Upgrading to 4.5</link></paragraph></li></ul><line>Find more&amp;nbsp;<link target=\"_blank\" url_id=\"21\">tutorials</link>&amp;nbsp;and&amp;nbsp;<link target=\"_blank\" url_id=\"22\">videos</link> online.</line></paragraph></section></section></section>\n',
  'ezxmltext',
  104,
  'eng-GB',
  3,
  0,
  '',
  6
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  156,
  41,
  0,
  1045487555,
  '',
  'ezxmltext',
  105,
  'eng-GB',
  3,
  0,
  '',
  1
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  108,
  158,
  1,
  0,
  0,
  '',
  'ezboolean',
  108,
  'eng-GB',
  3,
  0,
  '',
  6
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  158,
  41,
  0,
  0,
  '',
  'ezboolean',
  109,
  'eng-GB',
  3,
  0,
  '',
  1
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  4,
  45,
  0,
  0,
  'Setup',
  'ezstring',
  123,
  'eng-GB',
  3,
  0,
  'setup',
  1
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  155,
  45,
  0,
  0,
  '',
  'ezstring',
  124,
  'eng-GB',
  3,
  0,
  '',
  1
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  119,
  45,
  0,
  1045487555,
  '<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',
  'ezxmltext',
  125,
  'eng-GB',
  3,
  0,
  '',
  1
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  156,
  45,
  0,
  1045487555,
  '<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',
  'ezxmltext',
  126,
  'eng-GB',
  3,
  0,
  '',
  1
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  158,
  45,
  0,
  0,
  '',
  'ezboolean',
  128,
  'eng-GB',
  3,
  0,
  '',
  1
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  4,
  49,
  0,
  0,
  'Images',
  'ezstring',
  142,
  'eng-GB',
  3,
  0,
  'images',
  1
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  155,
  49,
  0,
  0,
  '',
  'ezstring',
  143,
  'eng-GB',
  3,
  0,
  '',
  1
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  119,
  49,
  0,
  1045487555,
  '<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',
  'ezxmltext',
  144,
  'eng-GB',
  3,
  0,
  '',
  1
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  156,
  49,
  0,
  1045487555,
  '<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',
  'ezxmltext',
  145,
  'eng-GB',
  3,
  0,
  '',
  1
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  158,
  49,
  0,
  1,
  '',
  'ezboolean',
  146,
  'eng-GB',
  3,
  1,
  '',
  1
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  4,
  50,
  0,
  0,
  'Files',
  'ezstring',
  147,
  'eng-GB',
  3,
  0,
  'files',
  1
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  155,
  50,
  0,
  0,
  '',
  'ezstring',
  148,
  'eng-GB',
  3,
  0,
  '',
  1
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  119,
  50,
  0,
  1045487555,
  '<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',
  'ezxmltext',
  149,
  'eng-GB',
  3,
  0,
  '',
  1
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  156,
  50,
  0,
  1045487555,
  '<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',
  'ezxmltext',
  150,
  'eng-GB',
  3,
  0,
  '',
  1
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  158,
  50,
  0,
  1,
  '',
  'ezboolean',
  151,
  'eng-GB',
  3,
  1,
  '',
  1
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  4,
  51,
  0,
  0,
  'Multimedia',
  'ezstring',
  152,
  'eng-GB',
  3,
  0,
  'multimedia',
  1
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  155,
  51,
  0,
  0,
  '',
  'ezstring',
  153,
  'eng-GB',
  3,
  0,
  '',
  1
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  119,
  51,
  0,
  1045487555,
  '<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',
  'ezxmltext',
  154,
  'eng-GB',
  3,
  0,
  '',
  1
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  156,
  51,
  0,
  1045487555,
  '<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',
  'ezxmltext',
  155,
  'eng-GB',
  3,
  0,
  '',
  1
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  158,
  51,
  0,
  1,
  '',
  'ezboolean',
  156,
  'eng-GB',
  3,
  1,
  '',
  1
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  159,
  52,
  0,
  0,
  'Common INI settings',
  'ezstring',
  157,
  'eng-GB',
  2,
  0,
  'common ini settings',
  1
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  160,
  52,
  0,
  0,
  '/content/view/full/2/',
  'ezinisetting',
  158,
  'eng-GB',
  2,
  0,
  '',
  1
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  161,
  52,
  0,
  0,
  '/content/view/full/2',
  'ezinisetting',
  159,
  'eng-GB',
  2,
  0,
  '',
  1
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  162,
  52,
  0,
  0,
  'disabled',
  'ezinisetting',
  160,
  'eng-GB',
  2,
  0,
  '',
  1
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  163,
  52,
  0,
  0,
  'disabled',
  'ezinisetting',
  161,
  'eng-GB',
  2,
  0,
  '',
  1
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  164,
  52,
  0,
  0,
  '',
  'ezinisetting',
  162,
  'eng-GB',
  2,
  0,
  '',
  1
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  165,
  52,
  0,
  0,
  'enabled',
  'ezinisetting',
  163,
  'eng-GB',
  2,
  0,
  '',
  1
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  166,
  52,
  0,
  0,
  'disabled',
  'ezinisetting',
  164,
  'eng-GB',
  2,
  0,
  '',
  1
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  167,
  52,
  0,
  0,
  'enabled',
  'ezinisetting',
  165,
  'eng-GB',
  2,
  0,
  '',
  1
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  168,
  52,
  0,
  0,
  'enabled',
  'ezinisetting',
  166,
  'eng-GB',
  2,
  0,
  '',
  1
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  169,
  52,
  0,
  0,
  '=geometry/scale=100;100',
  'ezinisetting',
  167,
  'eng-GB',
  2,
  0,
  '',
  1
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  170,
  52,
  0,
  0,
  '=geometry/scale=200;200',
  'ezinisetting',
  168,
  'eng-GB',
  2,
  0,
  '',
  1
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  171,
  52,
  0,
  0,
  '=geometry/scale=300;300',
  'ezinisetting',
  169,
  'eng-GB',
  2,
  0,
  '',
  1
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  172,
  54,
  0,
  0,
  'Plain site',
  'ezinisetting',
  170,
  'eng-GB',
  2,
  0,
  '',
  2
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  173,
  54,
  0,
  0,
  'author=eZ Systems\ncopyright=eZ Systems\ndescription=Content Management System\nkeywords=cms, publish, e-commerce, content management, development framework',
  'ezinisetting',
  171,
  'eng-GB',
  2,
  0,
  '',
  2
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  174,
  54,
  0,
  0,
  '<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<ezimage serial_number=\"1\" is_valid=\"\" filename=\"ez_publish.\" suffix=\"\" basename=\"ez_publish\" dirpath=\"var/storage/images/setup/ez_publish/172-1-eng-GB\" url=\"var/storage/images/setup/ez_publish/172-1-eng-GB/ez_publish.\" original_filename=\"\" mime_type=\"\" width=\"\" height=\"\" alternative_text=\"\" alias_key=\"1293033771\" timestamp=\"1082016632\"><original attribute_id=\"172\" attribute_version=\"2\" attribute_language=\"eng-GB\"/></ezimage>\n',
  'ezimage',
  172,
  'eng-GB',
  2,
  0,
  '',
  2
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  175,
  54,
  0,
  0,
  '0',
  'ezpackage',
  173,
  'eng-GB',
  2,
  0,
  '0',
  2
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  176,
  54,
  0,
  0,
  'sitestyle_identifier',
  'ezstring',
  174,
  'eng-GB',
  2,
  0,
  'sitestyle_identifier',
  2
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  177,
  54,
  0,
  0,
  'nospam@ez.no',
  'ezinisetting',
  175,
  'eng-GB',
  2,
  0,
  '',
  2
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  178,
  54,
  0,
  0,
  'ez.no',
  'ezinisetting',
  176,
  'eng-GB',
  2,
  0,
  '',
  2
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  179,
  10,
  0,
  0,
  '',
  'eztext',
  177,
  'eng-GB',
  3,
  0,
  '',
  2
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  179,
  14,
  0,
  0,
  '',
  'eztext',
  178,
  'eng-GB',
  3,
  0,
  '',
  3
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  180,
  10,
  0,
  0,
  '',
  'ezimage',
  179,
  'eng-GB',
  3,
  0,
  '',
  2
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  180,
  14,
  0,
  0,
  '<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<ezimage serial_number=\"1\" is_valid=\"\" filename=\"\" suffix=\"\" basename=\"\" dirpath=\"\" url=\"\" original_filename=\"\" mime_type=\"\" width=\"\" height=\"\" alternative_text=\"\" alias_key=\"1293033771\" timestamp=\"1301057722\"><original attribute_id=\"180\" attribute_version=\"3\" attribute_language=\"eng-GB\"/></ezimage>\n',
  'ezimage',
  180,
  'eng-GB',
  3,
  0,
  '',
  3
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  4,
  56,
  0,
  NULL,
  'Design',
  'ezstring',
  181,
  'eng-GB',
  3,
  0,
  'design',
  1
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  155,
  56,
  0,
  NULL,
  '',
  'ezstring',
  182,
  'eng-GB',
  3,
  0,
  '',
  1
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  119,
  56,
  0,
  1045487555,
  '<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',
  'ezxmltext',
  183,
  'eng-GB',
  3,
  0,
  '',
  1
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  156,
  56,
  0,
  1045487555,
  '<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',
  'ezxmltext',
  184,
  'eng-GB',
  3,
  0,
  '',
  1
);
INSERT INTO ezcontentobject_attribute (
  attribute_original_id,
  contentclassattribute_id,
  contentobject_id,
  data_float,
  data_int,
  data_text,
  data_type_string,
  id,
  language_code,
  language_id,
  sort_key_int,
  sort_key_string,
  version
) VALUES (
  0,
  158,
  56,
  0,
  1,
  '',
  'ezboolean',
  185,
  'eng-GB',
  3,
  1,
  '',
  1
);

INSERT INTO ezcontentobject_name (
  content_translation,
  content_version,
  contentobject_id,
  language_id,
  name,
  real_translation
) VALUES (
  'eng-GB',
  6,
  1,
  3,
  'eZ Publish',
  'eng-GB'
);
INSERT INTO ezcontentobject_name (
  content_translation,
  content_version,
  contentobject_id,
  language_id,
  name,
  real_translation
) VALUES (
  'eng-GB',
  1,
  4,
  3,
  'Users',
  'eng-GB'
);
INSERT INTO ezcontentobject_name (
  content_translation,
  content_version,
  contentobject_id,
  language_id,
  name,
  real_translation
) VALUES (
  'eng-GB',
  2,
  10,
  3,
  'Anonymous User',
  'eng-GB'
);
INSERT INTO ezcontentobject_name (
  content_translation,
  content_version,
  contentobject_id,
  language_id,
  name,
  real_translation
) VALUES (
  'eng-GB',
  1,
  11,
  3,
  'Guest accounts',
  'eng-GB'
);
INSERT INTO ezcontentobject_name (
  content_translation,
  content_version,
  contentobject_id,
  language_id,
  name,
  real_translation
) VALUES (
  'eng-GB',
  1,
  12,
  3,
  'Administrator users',
  'eng-GB'
);
INSERT INTO ezcontentobject_name (
  content_translation,
  content_version,
  contentobject_id,
  language_id,
  name,
  real_translation
) VALUES (
  'eng-GB',
  1,
  13,
  3,
  'Editors',
  'eng-GB'
);
INSERT INTO ezcontentobject_name (
  content_translation,
  content_version,
  contentobject_id,
  language_id,
  name,
  real_translation
) VALUES (
  'eng-GB',
  3,
  14,
  3,
  'Administrator User',
  'eng-GB'
);
INSERT INTO ezcontentobject_name (
  content_translation,
  content_version,
  contentobject_id,
  language_id,
  name,
  real_translation
) VALUES (
  'eng-GB',
  1,
  41,
  3,
  'Media',
  'eng-GB'
);
INSERT INTO ezcontentobject_name (
  content_translation,
  content_version,
  contentobject_id,
  language_id,
  name,
  real_translation
) VALUES (
  'eng-GB',
  1,
  42,
  3,
  'Anonymous Users',
  'eng-GB'
);
INSERT INTO ezcontentobject_name (
  content_translation,
  content_version,
  contentobject_id,
  language_id,
  name,
  real_translation
) VALUES (
  'eng-GB',
  1,
  45,
  3,
  'Setup',
  'eng-GB'
);
INSERT INTO ezcontentobject_name (
  content_translation,
  content_version,
  contentobject_id,
  language_id,
  name,
  real_translation
) VALUES (
  'eng-GB',
  1,
  49,
  3,
  'Images',
  'eng-GB'
);
INSERT INTO ezcontentobject_name (
  content_translation,
  content_version,
  contentobject_id,
  language_id,
  name,
  real_translation
) VALUES (
  'eng-GB',
  1,
  50,
  3,
  'Files',
  'eng-GB'
);
INSERT INTO ezcontentobject_name (
  content_translation,
  content_version,
  contentobject_id,
  language_id,
  name,
  real_translation
) VALUES (
  'eng-GB',
  1,
  51,
  3,
  'Multimedia',
  'eng-GB'
);
INSERT INTO ezcontentobject_name (
  content_translation,
  content_version,
  contentobject_id,
  language_id,
  name,
  real_translation
) VALUES (
  'eng-GB',
  1,
  52,
  2,
  'Common INI settings',
  'eng-GB'
);
INSERT INTO ezcontentobject_name (
  content_translation,
  content_version,
  contentobject_id,
  language_id,
  name,
  real_translation
) VALUES (
  'eng-GB',
  2,
  54,
  2,
  'Plain site',
  'eng-GB'
);
INSERT INTO ezcontentobject_name (
  content_translation,
  content_version,
  contentobject_id,
  language_id,
  name,
  real_translation
) VALUES (
  'eng-GB',
  1,
  56,
  3,
  'Design',
  'eng-GB'
);

INSERT INTO ezcontentobject_tree (
  contentobject_id,
  contentobject_is_published,
  contentobject_version,
  depth,
  is_hidden,
  is_invisible,
  main_node_id,
  modified_subnode,
  node_id,
  parent_node_id,
  path_identification_string,
  path_string,
  priority,
  remote_id,
  sort_field,
  sort_order
) VALUES (
  0,
  1,
  1,
  0,
  0,
  0,
  1,
  1301073466,
  1,
  1,
  '',
  '/1/',
  0,
  '629709ba256fe317c3ddcee35453a96a',
  1,
  1
);
INSERT INTO ezcontentobject_tree (
  contentobject_id,
  contentobject_is_published,
  contentobject_version,
  depth,
  is_hidden,
  is_invisible,
  main_node_id,
  modified_subnode,
  node_id,
  parent_node_id,
  path_identification_string,
  path_string,
  priority,
  remote_id,
  sort_field,
  sort_order
) VALUES (
  1,
  1,
  6,
  1,
  0,
  0,
  2,
  1301073466,
  2,
  1,
  '',
  '/1/2/',
  0,
  'f3e90596361e31d496d4026eb624c983',
  8,
  1
);
INSERT INTO ezcontentobject_tree (
  contentobject_id,
  contentobject_is_published,
  contentobject_version,
  depth,
  is_hidden,
  is_invisible,
  main_node_id,
  modified_subnode,
  node_id,
  parent_node_id,
  path_identification_string,
  path_string,
  priority,
  remote_id,
  sort_field,
  sort_order
) VALUES (
  4,
  1,
  1,
  1,
  0,
  0,
  5,
  1301062024,
  5,
  1,
  'users',
  '/1/5/',
  0,
  '3f6d92f8044aed134f32153517850f5a',
  1,
  1
);
INSERT INTO ezcontentobject_tree (
  contentobject_id,
  contentobject_is_published,
  contentobject_version,
  depth,
  is_hidden,
  is_invisible,
  main_node_id,
  modified_subnode,
  node_id,
  parent_node_id,
  path_identification_string,
  path_string,
  priority,
  remote_id,
  sort_field,
  sort_order
) VALUES (
  11,
  1,
  1,
  2,
  0,
  0,
  12,
  1081860719,
  12,
  5,
  'users/guest_accounts',
  '/1/5/12/',
  0,
  '602dcf84765e56b7f999eaafd3821dd3',
  1,
  1
);
INSERT INTO ezcontentobject_tree (
  contentobject_id,
  contentobject_is_published,
  contentobject_version,
  depth,
  is_hidden,
  is_invisible,
  main_node_id,
  modified_subnode,
  node_id,
  parent_node_id,
  path_identification_string,
  path_string,
  priority,
  remote_id,
  sort_field,
  sort_order
) VALUES (
  12,
  1,
  1,
  2,
  0,
  0,
  13,
  1301062024,
  13,
  5,
  'users/administrator_users',
  '/1/5/13/',
  0,
  '769380b7aa94541679167eab817ca893',
  1,
  1
);
INSERT INTO ezcontentobject_tree (
  contentobject_id,
  contentobject_is_published,
  contentobject_version,
  depth,
  is_hidden,
  is_invisible,
  main_node_id,
  modified_subnode,
  node_id,
  parent_node_id,
  path_identification_string,
  path_string,
  priority,
  remote_id,
  sort_field,
  sort_order
) VALUES (
  13,
  1,
  1,
  2,
  0,
  0,
  14,
  1081860719,
  14,
  5,
  'users/editors',
  '/1/5/14/',
  0,
  'f7dda2854fc68f7c8455d9cb14bd04a9',
  1,
  1
);
INSERT INTO ezcontentobject_tree (
  contentobject_id,
  contentobject_is_published,
  contentobject_version,
  depth,
  is_hidden,
  is_invisible,
  main_node_id,
  modified_subnode,
  node_id,
  parent_node_id,
  path_identification_string,
  path_string,
  priority,
  remote_id,
  sort_field,
  sort_order
) VALUES (
  14,
  1,
  3,
  3,
  0,
  0,
  15,
  1301062024,
  15,
  13,
  'users/administrator_users/administrator_user',
  '/1/5/13/15/',
  0,
  'e5161a99f733200b9ed4e80f9c16187b',
  1,
  1
);
INSERT INTO ezcontentobject_tree (
  contentobject_id,
  contentobject_is_published,
  contentobject_version,
  depth,
  is_hidden,
  is_invisible,
  main_node_id,
  modified_subnode,
  node_id,
  parent_node_id,
  path_identification_string,
  path_string,
  priority,
  remote_id,
  sort_field,
  sort_order
) VALUES (
  41,
  1,
  1,
  1,
  0,
  0,
  43,
  1081860720,
  43,
  1,
  'media',
  '/1/43/',
  0,
  '75c715a51699d2d309a924eca6a95145',
  9,
  1
);
INSERT INTO ezcontentobject_tree (
  contentobject_id,
  contentobject_is_published,
  contentobject_version,
  depth,
  is_hidden,
  is_invisible,
  main_node_id,
  modified_subnode,
  node_id,
  parent_node_id,
  path_identification_string,
  path_string,
  priority,
  remote_id,
  sort_field,
  sort_order
) VALUES (
  42,
  1,
  1,
  2,
  0,
  0,
  44,
  1081860719,
  44,
  5,
  'users/anonymous_users',
  '/1/5/44/',
  0,
  '4fdf0072da953bb276c0c7e0141c5c9b',
  9,
  1
);
INSERT INTO ezcontentobject_tree (
  contentobject_id,
  contentobject_is_published,
  contentobject_version,
  depth,
  is_hidden,
  is_invisible,
  main_node_id,
  modified_subnode,
  node_id,
  parent_node_id,
  path_identification_string,
  path_string,
  priority,
  remote_id,
  sort_field,
  sort_order
) VALUES (
  10,
  1,
  2,
  3,
  0,
  0,
  45,
  1081860719,
  45,
  44,
  'users/anonymous_users/anonymous_user',
  '/1/5/44/45/',
  0,
  '2cf8343bee7b482bab82b269d8fecd76',
  9,
  1
);
INSERT INTO ezcontentobject_tree (
  contentobject_id,
  contentobject_is_published,
  contentobject_version,
  depth,
  is_hidden,
  is_invisible,
  main_node_id,
  modified_subnode,
  node_id,
  parent_node_id,
  path_identification_string,
  path_string,
  priority,
  remote_id,
  sort_field,
  sort_order
) VALUES (
  45,
  1,
  1,
  1,
  0,
  0,
  48,
  1184592117,
  48,
  1,
  'setup2',
  '/1/48/',
  0,
  '182ce1b5af0c09fa378557c462ba2617',
  9,
  1
);
INSERT INTO ezcontentobject_tree (
  contentobject_id,
  contentobject_is_published,
  contentobject_version,
  depth,
  is_hidden,
  is_invisible,
  main_node_id,
  modified_subnode,
  node_id,
  parent_node_id,
  path_identification_string,
  path_string,
  priority,
  remote_id,
  sort_field,
  sort_order
) VALUES (
  49,
  1,
  1,
  2,
  0,
  0,
  51,
  1081860720,
  51,
  43,
  'media/images',
  '/1/43/51/',
  0,
  '1b26c0454b09bb49dfb1b9190ffd67cb',
  9,
  1
);
INSERT INTO ezcontentobject_tree (
  contentobject_id,
  contentobject_is_published,
  contentobject_version,
  depth,
  is_hidden,
  is_invisible,
  main_node_id,
  modified_subnode,
  node_id,
  parent_node_id,
  path_identification_string,
  path_string,
  priority,
  remote_id,
  sort_field,
  sort_order
) VALUES (
  50,
  1,
  1,
  2,
  0,
  0,
  52,
  1081860720,
  52,
  43,
  'media/files',
  '/1/43/52/',
  0,
  '0b113a208f7890f9ad3c24444ff5988c',
  9,
  1
);
INSERT INTO ezcontentobject_tree (
  contentobject_id,
  contentobject_is_published,
  contentobject_version,
  depth,
  is_hidden,
  is_invisible,
  main_node_id,
  modified_subnode,
  node_id,
  parent_node_id,
  path_identification_string,
  path_string,
  priority,
  remote_id,
  sort_field,
  sort_order
) VALUES (
  51,
  1,
  1,
  2,
  0,
  0,
  53,
  1081860720,
  53,
  43,
  'media/multimedia',
  '/1/43/53/',
  0,
  '4f18b82c75f10aad476cae5adf98c11f',
  9,
  1
);
INSERT INTO ezcontentobject_tree (
  contentobject_id,
  contentobject_is_published,
  contentobject_version,
  depth,
  is_hidden,
  is_invisible,
  main_node_id,
  modified_subnode,
  node_id,
  parent_node_id,
  path_identification_string,
  path_string,
  priority,
  remote_id,
  sort_field,
  sort_order
) VALUES (
  52,
  1,
  1,
  2,
  0,
  0,
  54,
  1184592117,
  54,
  48,
  'setup2/common_ini_settings',
  '/1/48/54/',
  0,
  'fa9f3cff9cf90ecfae335718dcbddfe2',
  1,
  1
);
INSERT INTO ezcontentobject_tree (
  contentobject_id,
  contentobject_is_published,
  contentobject_version,
  depth,
  is_hidden,
  is_invisible,
  main_node_id,
  modified_subnode,
  node_id,
  parent_node_id,
  path_identification_string,
  path_string,
  priority,
  remote_id,
  sort_field,
  sort_order
) VALUES (
  54,
  1,
  2,
  2,
  0,
  0,
  56,
  1301062376,
  56,
  58,
  'design/plain_site',
  '/1/58/56/',
  0,
  '772da20ecf88b3035d73cbdfcea0f119',
  1,
  1
);
INSERT INTO ezcontentobject_tree (
  contentobject_id,
  contentobject_is_published,
  contentobject_version,
  depth,
  is_hidden,
  is_invisible,
  main_node_id,
  modified_subnode,
  node_id,
  parent_node_id,
  path_identification_string,
  path_string,
  priority,
  remote_id,
  sort_field,
  sort_order
) VALUES (
  56,
  1,
  1,
  1,
  0,
  0,
  58,
  1301062376,
  58,
  1,
  'design',
  '/1/58/',
  0,
  '79f2d67372ab56f59b5d65bb9e0ca3b9',
  2,
  0
);

INSERT INTO ezcontentobject_version (
  contentobject_id,
  created,
  creator_id,
  id,
  initial_language_id,
  language_mask,
  modified,
  status,
  user_id,
  version,
  workflow_event_pos
) VALUES (
  4,
  0,
  14,
  4,
  2,
  3,
  0,
  1,
  0,
  1,
  1
);
INSERT INTO ezcontentobject_version (
  contentobject_id,
  created,
  creator_id,
  id,
  initial_language_id,
  language_mask,
  modified,
  status,
  user_id,
  version,
  workflow_event_pos
) VALUES (
  11,
  1033920737,
  14,
  439,
  2,
  3,
  1033920746,
  1,
  0,
  1,
  0
);
INSERT INTO ezcontentobject_version (
  contentobject_id,
  created,
  creator_id,
  id,
  initial_language_id,
  language_mask,
  modified,
  status,
  user_id,
  version,
  workflow_event_pos
) VALUES (
  12,
  1033920760,
  14,
  440,
  2,
  3,
  1033920775,
  1,
  0,
  1,
  0
);
INSERT INTO ezcontentobject_version (
  contentobject_id,
  created,
  creator_id,
  id,
  initial_language_id,
  language_mask,
  modified,
  status,
  user_id,
  version,
  workflow_event_pos
) VALUES (
  13,
  1033920786,
  14,
  441,
  2,
  3,
  1033920794,
  1,
  0,
  1,
  0
);
INSERT INTO ezcontentobject_version (
  contentobject_id,
  created,
  creator_id,
  id,
  initial_language_id,
  language_mask,
  modified,
  status,
  user_id,
  version,
  workflow_event_pos
) VALUES (
  41,
  1060695450,
  14,
  472,
  2,
  3,
  1060695457,
  1,
  0,
  1,
  0
);
INSERT INTO ezcontentobject_version (
  contentobject_id,
  created,
  creator_id,
  id,
  initial_language_id,
  language_mask,
  modified,
  status,
  user_id,
  version,
  workflow_event_pos
) VALUES (
  42,
  1072180278,
  14,
  473,
  2,
  3,
  1072180330,
  1,
  0,
  1,
  0
);
INSERT INTO ezcontentobject_version (
  contentobject_id,
  created,
  creator_id,
  id,
  initial_language_id,
  language_mask,
  modified,
  status,
  user_id,
  version,
  workflow_event_pos
) VALUES (
  10,
  1072180337,
  14,
  474,
  2,
  3,
  1072180405,
  1,
  0,
  2,
  0
);
INSERT INTO ezcontentobject_version (
  contentobject_id,
  created,
  creator_id,
  id,
  initial_language_id,
  language_mask,
  modified,
  status,
  user_id,
  version,
  workflow_event_pos
) VALUES (
  45,
  1079684084,
  14,
  477,
  2,
  3,
  1079684190,
  1,
  0,
  1,
  0
);
INSERT INTO ezcontentobject_version (
  contentobject_id,
  created,
  creator_id,
  id,
  initial_language_id,
  language_mask,
  modified,
  status,
  user_id,
  version,
  workflow_event_pos
) VALUES (
  49,
  1080220181,
  14,
  488,
  2,
  3,
  1080220197,
  1,
  0,
  1,
  0
);
INSERT INTO ezcontentobject_version (
  contentobject_id,
  created,
  creator_id,
  id,
  initial_language_id,
  language_mask,
  modified,
  status,
  user_id,
  version,
  workflow_event_pos
) VALUES (
  50,
  1080220211,
  14,
  489,
  2,
  3,
  1080220220,
  1,
  0,
  1,
  0
);
INSERT INTO ezcontentobject_version (
  contentobject_id,
  created,
  creator_id,
  id,
  initial_language_id,
  language_mask,
  modified,
  status,
  user_id,
  version,
  workflow_event_pos
) VALUES (
  51,
  1080220225,
  14,
  490,
  2,
  3,
  1080220233,
  1,
  0,
  1,
  0
);
INSERT INTO ezcontentobject_version (
  contentobject_id,
  created,
  creator_id,
  id,
  initial_language_id,
  language_mask,
  modified,
  status,
  user_id,
  version,
  workflow_event_pos
) VALUES (
  52,
  1082016497,
  14,
  491,
  2,
  3,
  1082016591,
  1,
  0,
  1,
  0
);
INSERT INTO ezcontentobject_version (
  contentobject_id,
  created,
  creator_id,
  id,
  initial_language_id,
  language_mask,
  modified,
  status,
  user_id,
  version,
  workflow_event_pos
) VALUES (
  56,
  1103023120,
  14,
  495,
  2,
  3,
  1103023120,
  1,
  0,
  1,
  0
);
INSERT INTO ezcontentobject_version (
  contentobject_id,
  created,
  creator_id,
  id,
  initial_language_id,
  language_mask,
  modified,
  status,
  user_id,
  version,
  workflow_event_pos
) VALUES (
  14,
  1301061783,
  14,
  499,
  2,
  3,
  1301062024,
  1,
  0,
  3,
  0
);
INSERT INTO ezcontentobject_version (
  contentobject_id,
  created,
  creator_id,
  id,
  initial_language_id,
  language_mask,
  modified,
  status,
  user_id,
  version,
  workflow_event_pos
) VALUES (
  54,
  1301062300,
  14,
  500,
  2,
  3,
  1301062375,
  1,
  0,
  2,
  0
);
INSERT INTO ezcontentobject_version (
  contentobject_id,
  created,
  creator_id,
  id,
  initial_language_id,
  language_mask,
  modified,
  status,
  user_id,
  version,
  workflow_event_pos
) VALUES (
  1,
  1301072647,
  14,
  503,
  2,
  3,
  1301073466,
  1,
  0,
  6,
  1
);

INSERT INTO ezimagefile (
  contentobject_attribute_id,
  filepath,
  id
) VALUES (
  172,
  'var/storage/images/setup/ez_publish/172-1-eng-GB/ez_publish.',
  1
);

INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'English language',
  0,
  1
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'English language',
  1,
  2
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'French language',
  2,
  3
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'German language',
  3,
  4
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Japan',
  4,
  5
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Russian Federation and former USSR',
  5,
  6
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Iran',
  600,
  7
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Kazakhstan',
  601,
  8
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Indonesia',
  602,
  9
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Saudi Arabia',
  603,
  10
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Vietnam',
  604,
  11
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Turkey',
  605,
  12
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Romania',
  606,
  13
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Mexico',
  607,
  14
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Macedonia',
  608,
  15
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Lithuania',
  609,
  16
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Thailand',
  611,
  17
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Peru',
  612,
  18
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Mauritius',
  613,
  19
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Lebanon',
  614,
  20
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Hungary',
  615,
  21
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Thailand',
  616,
  22
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Ukraine',
  617,
  23
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'China, People\'s Republic',
  7,
  24
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Czech Republic and Slovakia',
  80,
  25
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'India',
  81,
  26
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Norway',
  82,
  27
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Poland',
  83,
  28
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Spain',
  84,
  29
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Brazil',
  85,
  30
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Serbia and Montenegro',
  86,
  31
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Denmark',
  87,
  32
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Italy',
  88,
  33
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Korea, Republic',
  89,
  34
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Netherlands',
  90,
  35
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Sweden',
  91,
  36
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'International NGO Publishers and EC Organizations',
  92,
  37
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'India',
  93,
  38
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Netherlands',
  94,
  39
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Argentina',
  950,
  40
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Finland',
  951,
  41
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Finland',
  952,
  42
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Croatia',
  953,
  43
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Bulgaria',
  954,
  44
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Sri Lanka',
  955,
  45
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Chile',
  956,
  46
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Taiwan',
  957,
  47
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Colombia',
  958,
  48
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Cuba',
  959,
  49
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Greece',
  960,
  50
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Slovenia',
  961,
  51
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Hong Kong, China',
  962,
  52
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Hungary',
  963,
  53
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Iran',
  964,
  54
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Israel',
  965,
  55
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Ukraine',
  966,
  56
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Malaysia',
  967,
  57
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Mexico',
  968,
  58
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Pakistan',
  969,
  59
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Mexico',
  970,
  60
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Philippines',
  971,
  61
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Portugal',
  972,
  62
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Romania',
  973,
  63
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Thailand',
  974,
  64
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Turkey',
  975,
  65
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Caribbean Community',
  976,
  66
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Egypt',
  977,
  67
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Nigeria',
  978,
  68
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Indonesia',
  979,
  69
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Venezuela',
  980,
  70
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Singapore',
  981,
  71
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'South Pacific',
  982,
  72
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Malaysia',
  983,
  73
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Bangladesh',
  984,
  74
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Belarus',
  985,
  75
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Taiwan',
  986,
  76
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Argentina',
  987,
  77
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Hong Kong, China',
  988,
  78
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Portugal',
  989,
  79
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Qatar',
  9927,
  80
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Albania',
  9928,
  81
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Guatemala',
  9929,
  82
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Costa Rica',
  9930,
  83
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Algeria',
  9931,
  84
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Lao People\'s Democratic Republic',
  9932,
  85
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Syria',
  9933,
  86
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Latvia',
  9934,
  87
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Iceland',
  9935,
  88
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Afghanistan',
  9936,
  89
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Nepal',
  9937,
  90
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Tunisia',
  9938,
  91
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Armenia',
  9939,
  92
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Montenegro',
  9940,
  93
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Georgia',
  9941,
  94
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Ecuador',
  9942,
  95
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Uzbekistan',
  9943,
  96
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Turkey',
  9944,
  97
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Dominican Republic',
  9945,
  98
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Korea, P.D.R.',
  9946,
  99
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Algeria',
  9947,
  100
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'United Arab Emirates',
  9948,
  101
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Estonia',
  9949,
  102
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Palestine',
  9950,
  103
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Kosova',
  9951,
  104
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Azerbaijan',
  9952,
  105
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Lebanon',
  9953,
  106
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Morocco',
  9954,
  107
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Lithuania',
  9955,
  108
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Cameroon',
  9956,
  109
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Jordan',
  9957,
  110
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Bosnia and Herzegovina',
  9958,
  111
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Libya',
  9959,
  112
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Saudi Arabia',
  9960,
  113
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Algeria',
  9961,
  114
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Panama',
  9962,
  115
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Cyprus',
  9963,
  116
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Ghana',
  9964,
  117
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Kazakhstan',
  9965,
  118
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Kenya',
  9966,
  119
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Kyrgyz Republic',
  9967,
  120
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Costa Rica',
  9968,
  121
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Uganda',
  9970,
  122
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Singapore',
  9971,
  123
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Peru',
  9972,
  124
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Tunisia',
  9973,
  125
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Uruguay',
  9974,
  126
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Moldova',
  9975,
  127
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Tanzania',
  9976,
  128
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Costa Rica',
  9977,
  129
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Ecuador',
  9978,
  130
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Iceland',
  9979,
  131
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Papua New Guinea',
  9980,
  132
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Morocco',
  9981,
  133
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Zambia',
  9982,
  134
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Gambia',
  9983,
  135
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Latvia',
  9984,
  136
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Estonia',
  9985,
  137
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Lithuania',
  9986,
  138
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Tanzania',
  9987,
  139
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Ghana',
  9988,
  140
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Macedonia',
  9989,
  141
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Bahrain',
  99901,
  142
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Gabon',
  99902,
  143
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Mauritius',
  99903,
  144
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Netherlands Antilles and Aruba',
  99904,
  145
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Bolivia',
  99905,
  146
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Kuwait',
  99906,
  147
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Malawi',
  99908,
  148
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Malta',
  99909,
  149
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Sierra Leone',
  99910,
  150
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Lesotho',
  99911,
  151
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Botswana',
  99912,
  152
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Andorra',
  99913,
  153
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Suriname',
  99914,
  154
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Maldives',
  99915,
  155
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Namibia',
  99916,
  156
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Brunei Darussalam',
  99917,
  157
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Faroe Islands',
  99918,
  158
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Benin',
  99919,
  159
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Andorra',
  99920,
  160
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Qatar',
  99921,
  161
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Guatemala',
  99922,
  162
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'El Salvador',
  99923,
  163
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Nicaragua',
  99924,
  164
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Paraguay',
  99925,
  165
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Honduras',
  99926,
  166
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Albania',
  99927,
  167
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Georgia',
  99928,
  168
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Mongolia',
  99929,
  169
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Armenia',
  99930,
  170
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Seychelles',
  99931,
  171
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Malta',
  99932,
  172
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Nepal',
  99933,
  173
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Dominican Republic',
  99934,
  174
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Haiti',
  99935,
  175
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Bhutan',
  99936,
  176
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Macau',
  99937,
  177
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Srpska, Republic of',
  99938,
  178
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Guatemala',
  99939,
  179
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Georgia',
  99940,
  180
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Armenia',
  99941,
  181
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Sudan',
  99942,
  182
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Albania',
  99943,
  183
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Ethiopia',
  99944,
  184
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Namibia',
  99945,
  185
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Nepal',
  99946,
  186
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Tajikistan',
  99947,
  187
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Eritrea',
  99948,
  188
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Mauritius',
  99949,
  189
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Cambodia',
  99950,
  190
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Congo',
  99951,
  191
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Mali',
  99952,
  192
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Paraguay',
  99953,
  193
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Bolivia',
  99954,
  194
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Srpska, Republic of',
  99955,
  195
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Albania',
  99956,
  196
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Malta',
  99957,
  197
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Bahrain',
  99958,
  198
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Luxembourg',
  99959,
  199
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Malawi',
  99960,
  200
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'El Salvador',
  99961,
  201
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Mongolia',
  99962,
  202
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Cambodia',
  99963,
  203
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Nicaragua',
  99964,
  204
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Macau',
  99965,
  205
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Kuwait',
  99966,
  206
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Paraguay',
  99967,
  207
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'Botswana',
  99968,
  208
);
INSERT INTO ezisbn_group (
  description,
  group_number,
  id
) VALUES (
  'France',
  10,
  209
);

INSERT INTO ezisbn_group_range (
  from_number,
  group_from,
  group_length,
  group_to,
  id,
  to_number
) VALUES (
  0,
  '0',
  1,
  '5',
  1,
  59999
);
INSERT INTO ezisbn_group_range (
  from_number,
  group_from,
  group_length,
  group_to,
  id,
  to_number
) VALUES (
  60000,
  '600',
  3,
  '649',
  2,
  64999
);
INSERT INTO ezisbn_group_range (
  from_number,
  group_from,
  group_length,
  group_to,
  id,
  to_number
) VALUES (
  70000,
  '7',
  1,
  '7',
  3,
  79999
);
INSERT INTO ezisbn_group_range (
  from_number,
  group_from,
  group_length,
  group_to,
  id,
  to_number
) VALUES (
  80000,
  '80',
  2,
  '94',
  4,
  94999
);
INSERT INTO ezisbn_group_range (
  from_number,
  group_from,
  group_length,
  group_to,
  id,
  to_number
) VALUES (
  95000,
  '950',
  3,
  '989',
  5,
  98999
);
INSERT INTO ezisbn_group_range (
  from_number,
  group_from,
  group_length,
  group_to,
  id,
  to_number
) VALUES (
  99000,
  '9900',
  4,
  '9989',
  6,
  99899
);
INSERT INTO ezisbn_group_range (
  from_number,
  group_from,
  group_length,
  group_to,
  id,
  to_number
) VALUES (
  99900,
  '99900',
  5,
  '99999',
  7,
  99999
);
INSERT INTO ezisbn_group_range (
  from_number,
  group_from,
  group_length,
  group_to,
  id,
  to_number
) VALUES (
  10000,
  '10',
  2,
  '10',
  8,
  10999
);

INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  1,
  1,
  '00',
  2,
  '19',
  19999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  20000,
  2,
  1,
  '200',
  3,
  '699',
  69999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  70000,
  3,
  1,
  '7000',
  4,
  '8499',
  84999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  85000,
  4,
  1,
  '85000',
  5,
  '89999',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  5,
  1,
  '900000',
  6,
  '949999',
  94999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  95000,
  6,
  1,
  '9500000',
  7,
  '9999999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  7,
  2,
  '00',
  2,
  '09',
  9999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  10000,
  8,
  2,
  '100',
  3,
  '399',
  39999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  40000,
  9,
  2,
  '4000',
  4,
  '5499',
  54999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  55000,
  10,
  2,
  '55000',
  5,
  '86979',
  86979
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  86980,
  11,
  2,
  '869800',
  6,
  '998999',
  99899
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  99900,
  12,
  2,
  '9990000',
  7,
  '9999999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  13,
  3,
  '00',
  2,
  '19',
  19999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  20000,
  14,
  3,
  '200',
  3,
  '349',
  34999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  35000,
  15,
  3,
  '35000',
  5,
  '39999',
  39999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  40000,
  16,
  3,
  '400',
  3,
  '699',
  69999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  70000,
  17,
  3,
  '7000',
  4,
  '8399',
  83999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  84000,
  18,
  3,
  '84000',
  5,
  '89999',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  19,
  3,
  '900000',
  6,
  '949999',
  94999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  95000,
  20,
  3,
  '9500000',
  7,
  '9999999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  21,
  4,
  '00',
  2,
  '02',
  2999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  3000,
  22,
  4,
  '030',
  3,
  '033',
  3399
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  3400,
  23,
  4,
  '0340',
  4,
  '0369',
  3699
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  3700,
  24,
  4,
  '03700',
  5,
  '03999',
  3999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  4000,
  25,
  4,
  '04',
  2,
  '19',
  19999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  20000,
  26,
  4,
  '200',
  3,
  '699',
  69999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  70000,
  27,
  4,
  '7000',
  4,
  '8499',
  84999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  85000,
  28,
  4,
  '85000',
  5,
  '89999',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  29,
  4,
  '900000',
  6,
  '949999',
  94999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  95000,
  30,
  4,
  '9500000',
  7,
  '9539999',
  95399
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  95400,
  31,
  4,
  '95400',
  5,
  '96999',
  96999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  97000,
  32,
  4,
  '9700000',
  7,
  '9899999',
  98999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  99000,
  33,
  4,
  '99000',
  5,
  '99499',
  99499
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  99500,
  34,
  4,
  '99500',
  5,
  '99999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  35,
  5,
  '00',
  2,
  '19',
  19999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  20000,
  36,
  5,
  '200',
  3,
  '699',
  69999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  70000,
  37,
  5,
  '7000',
  4,
  '8499',
  84999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  85000,
  38,
  5,
  '85000',
  5,
  '89999',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  39,
  5,
  '900000',
  6,
  '949999',
  94999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  95000,
  40,
  5,
  '9500000',
  7,
  '9999999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  41,
  6,
  '00',
  2,
  '19',
  19999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  20000,
  42,
  6,
  '200',
  3,
  '420',
  42099
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  42100,
  43,
  6,
  '4210',
  4,
  '4299',
  42999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  43000,
  44,
  6,
  '430',
  3,
  '430',
  43099
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  43100,
  45,
  6,
  '4310',
  4,
  '4399',
  43999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  44000,
  46,
  6,
  '440',
  3,
  '440',
  44099
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  44100,
  47,
  6,
  '4410',
  4,
  '4499',
  44999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  45000,
  48,
  6,
  '450',
  3,
  '699',
  69999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  70000,
  49,
  6,
  '7000',
  4,
  '8499',
  84999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  85000,
  50,
  6,
  '85000',
  5,
  '89999',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  51,
  6,
  '900000',
  6,
  '909999',
  90999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  91000,
  52,
  6,
  '91000',
  5,
  '91999',
  91999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  92000,
  53,
  6,
  '9200',
  4,
  '9299',
  92999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  93000,
  54,
  6,
  '93000',
  5,
  '94999',
  94999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  95000,
  55,
  6,
  '9500000',
  7,
  '9500999',
  95009
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  95010,
  56,
  6,
  '9501',
  4,
  '9799',
  97999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  98000,
  57,
  6,
  '98000',
  5,
  '98999',
  98999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  99000,
  58,
  6,
  '9900000',
  7,
  '9909999',
  99099
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  99100,
  59,
  6,
  '9910',
  4,
  '9999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  60,
  7,
  '00',
  2,
  '09',
  9999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  10000,
  61,
  7,
  '100',
  3,
  '499',
  49999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  50000,
  62,
  7,
  '5000',
  4,
  '8999',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  63,
  7,
  '90000',
  5,
  '99999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  64,
  8,
  '00',
  2,
  '19',
  19999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  20000,
  65,
  8,
  '200',
  3,
  '699',
  69999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  70000,
  66,
  8,
  '7000',
  4,
  '7999',
  79999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  80000,
  67,
  8,
  '80000',
  5,
  '84999',
  84999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  85000,
  68,
  8,
  '85',
  2,
  '99',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  69,
  9,
  '00',
  2,
  '19',
  19999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  20000,
  70,
  9,
  '200',
  3,
  '799',
  79999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  80000,
  71,
  9,
  '8000',
  4,
  '9499',
  94999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  95000,
  72,
  9,
  '95000',
  5,
  '99999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  73,
  10,
  '00',
  2,
  '04',
  4999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  5000,
  74,
  10,
  '05',
  2,
  '49',
  49999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  50000,
  75,
  10,
  '500',
  3,
  '799',
  79999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  80000,
  76,
  10,
  '8000',
  4,
  '8999',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  77,
  10,
  '90000',
  5,
  '99999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  78,
  11,
  '0',
  1,
  '4',
  49999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  50000,
  79,
  11,
  '50',
  2,
  '89',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  80,
  11,
  '900',
  3,
  '979',
  97999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  98000,
  81,
  11,
  '9800',
  4,
  '9999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  1000,
  82,
  12,
  '01',
  2,
  '09',
  9999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  10000,
  83,
  12,
  '100',
  3,
  '399',
  39999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  40000,
  84,
  12,
  '4000',
  4,
  '5999',
  59999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  60000,
  85,
  12,
  '60000',
  5,
  '89999',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  86,
  12,
  '90',
  2,
  '99',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  87,
  13,
  '0',
  1,
  '0',
  9999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  10000,
  88,
  13,
  '10',
  2,
  '49',
  49999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  50000,
  89,
  13,
  '500',
  3,
  '799',
  79999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  80000,
  90,
  13,
  '8000',
  4,
  '9199',
  91999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  92000,
  91,
  13,
  '92000',
  5,
  '99999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  92,
  14,
  '00',
  2,
  '39',
  39999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  40000,
  93,
  14,
  '400',
  3,
  '749',
  74999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  75000,
  94,
  14,
  '7500',
  4,
  '9499',
  94999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  95000,
  95,
  14,
  '95000',
  5,
  '99999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  96,
  15,
  '0',
  1,
  '0',
  9999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  10000,
  97,
  15,
  '10',
  2,
  '19',
  19999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  20000,
  98,
  15,
  '200',
  3,
  '449',
  44999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  45000,
  99,
  15,
  '4500',
  4,
  '6499',
  64999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  65000,
  100,
  15,
  '65000',
  5,
  '69999',
  69999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  70000,
  101,
  15,
  '7',
  1,
  '9',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  102,
  16,
  '00',
  2,
  '39',
  39999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  40000,
  103,
  16,
  '400',
  3,
  '799',
  79999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  80000,
  104,
  16,
  '8000',
  4,
  '9499',
  94999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  95000,
  105,
  16,
  '95000',
  5,
  '99999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  106,
  18,
  '00',
  2,
  '29',
  29999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  30000,
  107,
  18,
  '300',
  3,
  '399',
  39999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  40000,
  108,
  18,
  '4000',
  4,
  '4499',
  44999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  45000,
  109,
  18,
  '45000',
  5,
  '49999',
  49999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  50000,
  110,
  18,
  '50',
  2,
  '99',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  111,
  19,
  '0',
  1,
  '9',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  112,
  20,
  '00',
  2,
  '39',
  39999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  40000,
  113,
  20,
  '400',
  3,
  '799',
  79999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  80000,
  114,
  20,
  '8000',
  4,
  '9499',
  94999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  95000,
  115,
  20,
  '95000',
  5,
  '99999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  116,
  21,
  '00',
  2,
  '09',
  9999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  10000,
  117,
  21,
  '100',
  3,
  '499',
  49999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  50000,
  118,
  21,
  '5000',
  4,
  '7999',
  79999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  80000,
  119,
  21,
  '80000',
  5,
  '89999',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  120,
  22,
  '00',
  2,
  '19',
  19999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  20000,
  121,
  22,
  '200',
  3,
  '699',
  69999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  70000,
  122,
  22,
  '7000',
  4,
  '8999',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  123,
  22,
  '90000',
  5,
  '99999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  124,
  23,
  '00',
  2,
  '49',
  49999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  50000,
  125,
  23,
  '500',
  3,
  '699',
  69999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  70000,
  126,
  23,
  '7000',
  4,
  '8999',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  127,
  23,
  '90000',
  5,
  '99999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  128,
  24,
  '00',
  2,
  '09',
  9999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  10000,
  129,
  24,
  '100',
  3,
  '499',
  49999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  50000,
  130,
  24,
  '5000',
  4,
  '7999',
  79999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  80000,
  131,
  24,
  '80000',
  5,
  '89999',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  132,
  24,
  '900000',
  6,
  '999999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  133,
  25,
  '00',
  2,
  '19',
  19999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  20000,
  134,
  25,
  '200',
  3,
  '699',
  69999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  70000,
  135,
  25,
  '7000',
  4,
  '8499',
  84999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  85000,
  136,
  25,
  '85000',
  5,
  '89999',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  137,
  25,
  '900000',
  6,
  '999999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  138,
  26,
  '00',
  2,
  '19',
  19999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  20000,
  139,
  26,
  '200',
  3,
  '699',
  69999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  70000,
  140,
  26,
  '7000',
  4,
  '8499',
  84999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  85000,
  141,
  26,
  '85000',
  5,
  '89999',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  142,
  26,
  '900000',
  6,
  '999999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  143,
  27,
  '00',
  2,
  '19',
  19999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  20000,
  144,
  27,
  '200',
  3,
  '699',
  69999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  70000,
  145,
  27,
  '7000',
  4,
  '8999',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  146,
  27,
  '90000',
  5,
  '98999',
  98999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  99000,
  147,
  27,
  '990000',
  6,
  '999999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  148,
  28,
  '00',
  2,
  '19',
  19999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  20000,
  149,
  28,
  '200',
  3,
  '599',
  59999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  60000,
  150,
  28,
  '60000',
  5,
  '69999',
  69999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  70000,
  151,
  28,
  '7000',
  4,
  '8499',
  84999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  85000,
  152,
  28,
  '85000',
  5,
  '89999',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  153,
  28,
  '900000',
  6,
  '999999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  154,
  29,
  '00',
  2,
  '14',
  14999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  15000,
  155,
  29,
  '15000',
  5,
  '19999',
  19999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  20000,
  156,
  29,
  '200',
  3,
  '699',
  69999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  70000,
  157,
  29,
  '7000',
  4,
  '8499',
  84999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  85000,
  158,
  29,
  '85000',
  5,
  '89999',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  159,
  29,
  '9000',
  4,
  '9199',
  91999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  92000,
  160,
  29,
  '920000',
  6,
  '923999',
  92399
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  92400,
  161,
  29,
  '92400',
  5,
  '92999',
  92999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  93000,
  162,
  29,
  '930000',
  6,
  '949999',
  94999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  95000,
  163,
  29,
  '95000',
  5,
  '96999',
  96999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  97000,
  164,
  29,
  '9700',
  4,
  '9999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  165,
  30,
  '00',
  2,
  '19',
  19999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  20000,
  166,
  30,
  '200',
  3,
  '599',
  59999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  60000,
  167,
  30,
  '60000',
  5,
  '69999',
  69999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  70000,
  168,
  30,
  '7000',
  4,
  '8499',
  84999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  85000,
  169,
  30,
  '85000',
  5,
  '89999',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  170,
  30,
  '900000',
  6,
  '979999',
  97999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  98000,
  171,
  30,
  '98000',
  5,
  '99999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  172,
  31,
  '00',
  2,
  '29',
  29999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  30000,
  173,
  31,
  '300',
  3,
  '599',
  59999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  60000,
  174,
  31,
  '6000',
  4,
  '7999',
  79999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  80000,
  175,
  31,
  '80000',
  5,
  '89999',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  176,
  31,
  '900000',
  6,
  '999999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  177,
  32,
  '00',
  2,
  '29',
  29999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  40000,
  178,
  32,
  '400',
  3,
  '649',
  64999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  70000,
  179,
  32,
  '7000',
  4,
  '7999',
  79999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  85000,
  180,
  32,
  '85000',
  5,
  '94999',
  94999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  97000,
  181,
  32,
  '970000',
  6,
  '999999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  182,
  33,
  '00',
  2,
  '19',
  19999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  20000,
  183,
  33,
  '200',
  3,
  '599',
  59999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  60000,
  184,
  33,
  '6000',
  4,
  '8499',
  84999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  85000,
  185,
  33,
  '85000',
  5,
  '89999',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  186,
  33,
  '900000',
  6,
  '949999',
  94999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  95000,
  187,
  33,
  '95000',
  5,
  '99999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  188,
  34,
  '00',
  2,
  '24',
  24999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  25000,
  189,
  34,
  '250',
  3,
  '549',
  54999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  55000,
  190,
  34,
  '5500',
  4,
  '8499',
  84999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  85000,
  191,
  34,
  '85000',
  5,
  '94999',
  94999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  95000,
  192,
  34,
  '950000',
  6,
  '969999',
  96999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  97000,
  193,
  34,
  '97000',
  5,
  '98999',
  98999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  99000,
  194,
  34,
  '990',
  3,
  '999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  195,
  35,
  '00',
  2,
  '19',
  19999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  20000,
  196,
  35,
  '200',
  3,
  '499',
  49999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  50000,
  197,
  35,
  '5000',
  4,
  '6999',
  69999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  70000,
  198,
  35,
  '70000',
  5,
  '79999',
  79999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  80000,
  199,
  35,
  '800000',
  6,
  '849999',
  84999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  85000,
  200,
  35,
  '8500',
  4,
  '8999',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  201,
  35,
  '90',
  2,
  '90',
  90999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  91000,
  202,
  35,
  '910000',
  6,
  '939999',
  93999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  94000,
  203,
  35,
  '94',
  2,
  '94',
  94999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  95000,
  204,
  35,
  '950000',
  6,
  '999999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  205,
  36,
  '0',
  1,
  '1',
  19999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  20000,
  206,
  36,
  '20',
  2,
  '49',
  49999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  50000,
  207,
  36,
  '500',
  3,
  '649',
  64999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  70000,
  208,
  36,
  '7000',
  4,
  '7999',
  79999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  85000,
  209,
  36,
  '85000',
  5,
  '94999',
  94999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  97000,
  210,
  36,
  '970000',
  6,
  '999999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  211,
  37,
  '0',
  1,
  '5',
  59999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  60000,
  212,
  37,
  '60',
  2,
  '79',
  79999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  80000,
  213,
  37,
  '800',
  3,
  '899',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  214,
  37,
  '9000',
  4,
  '9499',
  94999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  95000,
  215,
  37,
  '95000',
  5,
  '98999',
  98999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  99000,
  216,
  37,
  '990000',
  6,
  '999999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  217,
  38,
  '00',
  2,
  '09',
  9999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  10000,
  218,
  38,
  '100',
  3,
  '499',
  49999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  50000,
  219,
  38,
  '5000',
  4,
  '7999',
  79999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  80000,
  220,
  38,
  '80000',
  5,
  '94999',
  94999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  95000,
  221,
  38,
  '950000',
  6,
  '999999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  222,
  39,
  '000',
  3,
  '599',
  59999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  60000,
  223,
  39,
  '6000',
  4,
  '8999',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  224,
  39,
  '90000',
  5,
  '99999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  225,
  40,
  '00',
  2,
  '49',
  49999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  50000,
  226,
  40,
  '500',
  3,
  '899',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  227,
  40,
  '9000',
  4,
  '9899',
  98999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  99000,
  228,
  40,
  '99000',
  5,
  '99999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  229,
  41,
  '0',
  1,
  '1',
  19999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  20000,
  230,
  41,
  '20',
  2,
  '54',
  54999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  55000,
  231,
  41,
  '550',
  3,
  '889',
  88999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  89000,
  232,
  41,
  '8900',
  4,
  '9499',
  94999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  95000,
  233,
  41,
  '95000',
  5,
  '99999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  234,
  42,
  '00',
  2,
  '19',
  19999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  20000,
  235,
  42,
  '200',
  3,
  '499',
  49999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  50000,
  236,
  42,
  '5000',
  4,
  '5999',
  59999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  60000,
  237,
  42,
  '60',
  2,
  '65',
  65999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  66000,
  238,
  42,
  '6600',
  4,
  '6699',
  66999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  67000,
  239,
  42,
  '67000',
  5,
  '69999',
  69999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  70000,
  240,
  42,
  '7000',
  4,
  '7999',
  79999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  80000,
  241,
  42,
  '80',
  2,
  '94',
  94999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  95000,
  242,
  42,
  '9500',
  4,
  '9899',
  98999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  99000,
  243,
  42,
  '99000',
  5,
  '99999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  244,
  43,
  '0',
  1,
  '0',
  9999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  10000,
  245,
  43,
  '10',
  2,
  '14',
  14999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  15000,
  246,
  43,
  '150',
  3,
  '549',
  54999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  55000,
  247,
  43,
  '55000',
  5,
  '59999',
  59999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  60000,
  248,
  43,
  '6000',
  4,
  '9499',
  94999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  95000,
  249,
  43,
  '95000',
  5,
  '99999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  250,
  44,
  '00',
  2,
  '28',
  28999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  29000,
  251,
  44,
  '2900',
  4,
  '2999',
  29999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  30000,
  252,
  44,
  '300',
  3,
  '799',
  79999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  80000,
  253,
  44,
  '8000',
  4,
  '8999',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  254,
  44,
  '90000',
  5,
  '92999',
  92999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  93000,
  255,
  44,
  '9300',
  4,
  '9999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  256,
  45,
  '0000',
  4,
  '1999',
  19999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  20000,
  257,
  45,
  '20',
  2,
  '49',
  49999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  50000,
  258,
  45,
  '50000',
  5,
  '54999',
  54999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  55000,
  259,
  45,
  '550',
  3,
  '799',
  79999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  80000,
  260,
  45,
  '8000',
  4,
  '9499',
  94999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  95000,
  261,
  45,
  '95000',
  5,
  '99999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  262,
  46,
  '00',
  2,
  '19',
  19999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  20000,
  263,
  46,
  '200',
  3,
  '699',
  69999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  70000,
  264,
  46,
  '7000',
  4,
  '9999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  265,
  47,
  '00',
  2,
  '02',
  2999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  3000,
  266,
  47,
  '0300',
  4,
  '0499',
  4999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  5000,
  267,
  47,
  '05',
  2,
  '19',
  19999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  20000,
  268,
  47,
  '2000',
  4,
  '2099',
  20999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  21000,
  269,
  47,
  '21',
  2,
  '27',
  27999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  28000,
  270,
  47,
  '28000',
  5,
  '30999',
  30999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  31000,
  271,
  47,
  '31',
  2,
  '43',
  43999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  44000,
  272,
  47,
  '440',
  3,
  '819',
  81999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  82000,
  273,
  47,
  '8200',
  4,
  '9699',
  96999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  97000,
  274,
  47,
  '97000',
  5,
  '99999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  275,
  48,
  '00',
  2,
  '56',
  56999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  57000,
  276,
  48,
  '57000',
  5,
  '59999',
  59999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  60000,
  277,
  48,
  '600',
  3,
  '799',
  79999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  80000,
  278,
  48,
  '8000',
  4,
  '9499',
  94999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  95000,
  279,
  48,
  '95000',
  5,
  '99999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  280,
  49,
  '00',
  2,
  '19',
  19999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  20000,
  281,
  49,
  '200',
  3,
  '699',
  69999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  70000,
  282,
  49,
  '7000',
  4,
  '8499',
  84999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  85000,
  283,
  49,
  '85000',
  5,
  '99999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  284,
  50,
  '00',
  2,
  '19',
  19999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  20000,
  285,
  50,
  '200',
  3,
  '659',
  65999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  66000,
  286,
  50,
  '6600',
  4,
  '6899',
  68999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  69000,
  287,
  50,
  '690',
  3,
  '699',
  69999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  70000,
  288,
  50,
  '7000',
  4,
  '8499',
  84999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  85000,
  289,
  50,
  '85000',
  5,
  '92999',
  92999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  93000,
  290,
  50,
  '93',
  2,
  '93',
  93999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  94000,
  291,
  50,
  '9400',
  4,
  '9799',
  97999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  98000,
  292,
  50,
  '98000',
  5,
  '99999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  293,
  51,
  '00',
  2,
  '19',
  19999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  20000,
  294,
  51,
  '200',
  3,
  '599',
  59999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  60000,
  295,
  51,
  '6000',
  4,
  '8999',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  296,
  51,
  '90000',
  5,
  '94999',
  94999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  297,
  52,
  '00',
  2,
  '19',
  19999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  20000,
  298,
  52,
  '200',
  3,
  '699',
  69999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  70000,
  299,
  52,
  '7000',
  4,
  '8499',
  84999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  85000,
  300,
  52,
  '85000',
  5,
  '86999',
  86999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  87000,
  301,
  52,
  '8700',
  4,
  '8999',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  302,
  52,
  '900',
  3,
  '999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  303,
  53,
  '00',
  2,
  '19',
  19999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  20000,
  304,
  53,
  '200',
  3,
  '699',
  69999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  70000,
  305,
  53,
  '7000',
  4,
  '8499',
  84999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  85000,
  306,
  53,
  '85000',
  5,
  '89999',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  307,
  53,
  '9000',
  4,
  '9999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  308,
  54,
  '00',
  2,
  '14',
  14999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  15000,
  309,
  54,
  '150',
  3,
  '249',
  24999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  25000,
  310,
  54,
  '2500',
  4,
  '2999',
  29999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  30000,
  311,
  54,
  '300',
  3,
  '549',
  54999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  55000,
  312,
  54,
  '5500',
  4,
  '8999',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  313,
  54,
  '90000',
  5,
  '96999',
  96999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  97000,
  314,
  54,
  '970',
  3,
  '989',
  98999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  99000,
  315,
  54,
  '9900',
  4,
  '9999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  316,
  55,
  '00',
  2,
  '19',
  19999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  20000,
  317,
  55,
  '200',
  3,
  '599',
  59999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  70000,
  318,
  55,
  '7000',
  4,
  '7999',
  79999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  319,
  55,
  '90000',
  5,
  '99999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  320,
  56,
  '00',
  2,
  '14',
  14999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  15000,
  321,
  56,
  '1500',
  4,
  '1699',
  16999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  17000,
  322,
  56,
  '170',
  3,
  '199',
  19999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  20000,
  323,
  56,
  '2000',
  4,
  '2999',
  29999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  30000,
  324,
  56,
  '300',
  3,
  '699',
  69999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  70000,
  325,
  56,
  '7000',
  4,
  '8999',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  326,
  56,
  '90000',
  5,
  '99999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  327,
  57,
  '00',
  2,
  '00',
  999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  1000,
  328,
  57,
  '0100',
  4,
  '0999',
  9999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  10000,
  329,
  57,
  '10000',
  5,
  '19999',
  19999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  30000,
  330,
  57,
  '300',
  3,
  '499',
  49999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  50000,
  331,
  57,
  '5000',
  4,
  '5999',
  59999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  60000,
  332,
  57,
  '60',
  2,
  '89',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  333,
  57,
  '900',
  3,
  '989',
  98999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  99000,
  334,
  57,
  '9900',
  4,
  '9989',
  99899
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  99900,
  335,
  57,
  '99900',
  5,
  '99999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  1000,
  336,
  58,
  '01',
  2,
  '39',
  39999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  40000,
  337,
  58,
  '400',
  3,
  '499',
  49999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  50000,
  338,
  58,
  '5000',
  4,
  '7999',
  79999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  80000,
  339,
  58,
  '800',
  3,
  '899',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  340,
  58,
  '9000',
  4,
  '9999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  341,
  59,
  '0',
  1,
  '1',
  19999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  20000,
  342,
  59,
  '20',
  2,
  '39',
  39999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  40000,
  343,
  59,
  '400',
  3,
  '799',
  79999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  80000,
  344,
  59,
  '8000',
  4,
  '9999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  1000,
  345,
  60,
  '01',
  2,
  '59',
  59999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  60000,
  346,
  60,
  '600',
  3,
  '899',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  347,
  60,
  '9000',
  4,
  '9099',
  90999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  91000,
  348,
  60,
  '91000',
  5,
  '96999',
  96999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  97000,
  349,
  60,
  '9700',
  4,
  '9999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  350,
  61,
  '000',
  3,
  '015',
  1599
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  1600,
  351,
  61,
  '0160',
  4,
  '0199',
  1999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  2000,
  352,
  61,
  '02',
  2,
  '02',
  2999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  3000,
  353,
  61,
  '0300',
  4,
  '0599',
  5999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  6000,
  354,
  61,
  '06',
  2,
  '09',
  9999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  10000,
  355,
  61,
  '10',
  2,
  '49',
  49999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  50000,
  356,
  61,
  '500',
  3,
  '849',
  84999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  85000,
  357,
  61,
  '8500',
  4,
  '9099',
  90999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  91000,
  358,
  61,
  '91000',
  5,
  '98999',
  98999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  99000,
  359,
  61,
  '9900',
  4,
  '9999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  360,
  62,
  '0',
  1,
  '1',
  19999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  20000,
  361,
  62,
  '20',
  2,
  '54',
  54999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  55000,
  362,
  62,
  '550',
  3,
  '799',
  79999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  80000,
  363,
  62,
  '8000',
  4,
  '9499',
  94999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  95000,
  364,
  62,
  '95000',
  5,
  '99999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  365,
  63,
  '0',
  1,
  '0',
  9999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  10000,
  366,
  63,
  '100',
  3,
  '169',
  16999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  17000,
  367,
  63,
  '1700',
  4,
  '1999',
  19999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  20000,
  368,
  63,
  '20',
  2,
  '54',
  54999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  55000,
  369,
  63,
  '550',
  3,
  '759',
  75999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  76000,
  370,
  63,
  '7600',
  4,
  '8499',
  84999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  85000,
  371,
  63,
  '85000',
  5,
  '88999',
  88999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  89000,
  372,
  63,
  '8900',
  4,
  '9499',
  94999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  95000,
  373,
  63,
  '95000',
  5,
  '99999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  374,
  64,
  '00',
  2,
  '19',
  19999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  20000,
  375,
  64,
  '200',
  3,
  '699',
  69999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  70000,
  376,
  64,
  '7000',
  4,
  '8499',
  84999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  85000,
  377,
  64,
  '85000',
  5,
  '89999',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  378,
  64,
  '90000',
  5,
  '94999',
  94999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  95000,
  379,
  64,
  '9500',
  4,
  '9999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  380,
  65,
  '00000',
  5,
  '01999',
  1999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  2000,
  381,
  65,
  '02',
  2,
  '24',
  24999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  25000,
  382,
  65,
  '250',
  3,
  '599',
  59999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  60000,
  383,
  65,
  '6000',
  4,
  '9199',
  91999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  92000,
  384,
  65,
  '92000',
  5,
  '98999',
  98999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  99000,
  385,
  65,
  '990',
  3,
  '999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  386,
  66,
  '0',
  1,
  '3',
  39999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  40000,
  387,
  66,
  '40',
  2,
  '59',
  59999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  60000,
  388,
  66,
  '600',
  3,
  '799',
  79999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  80000,
  389,
  66,
  '8000',
  4,
  '9499',
  94999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  95000,
  390,
  66,
  '95000',
  5,
  '99999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  391,
  67,
  '00',
  2,
  '19',
  19999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  20000,
  392,
  67,
  '200',
  3,
  '499',
  49999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  50000,
  393,
  67,
  '5000',
  4,
  '6999',
  69999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  70000,
  394,
  67,
  '700',
  3,
  '999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  395,
  68,
  '000',
  3,
  '199',
  19999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  20000,
  396,
  68,
  '2000',
  4,
  '2999',
  29999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  30000,
  397,
  68,
  '30000',
  5,
  '79999',
  79999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  80000,
  398,
  68,
  '8000',
  4,
  '8999',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  399,
  68,
  '900',
  3,
  '999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  400,
  69,
  '000',
  3,
  '099',
  9999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  10000,
  401,
  69,
  '1000',
  4,
  '1499',
  14999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  15000,
  402,
  69,
  '15000',
  5,
  '19999',
  19999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  20000,
  403,
  69,
  '20',
  2,
  '29',
  29999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  30000,
  404,
  69,
  '3000',
  4,
  '3999',
  39999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  40000,
  405,
  69,
  '400',
  3,
  '799',
  79999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  80000,
  406,
  69,
  '8000',
  4,
  '9499',
  94999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  95000,
  407,
  69,
  '95000',
  5,
  '99999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  408,
  70,
  '00',
  2,
  '19',
  19999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  20000,
  409,
  70,
  '200',
  3,
  '599',
  59999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  60000,
  410,
  70,
  '6000',
  4,
  '9999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  411,
  71,
  '00',
  2,
  '11',
  11999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  12000,
  412,
  71,
  '1200',
  4,
  '1999',
  19999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  20000,
  413,
  71,
  '200',
  3,
  '289',
  28999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  29000,
  414,
  71,
  '2900',
  4,
  '9999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  415,
  72,
  '00',
  2,
  '09',
  9999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  10000,
  416,
  72,
  '100',
  3,
  '699',
  69999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  70000,
  417,
  72,
  '70',
  2,
  '89',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  418,
  72,
  '9000',
  4,
  '9799',
  97999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  98000,
  419,
  72,
  '98000',
  5,
  '99999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  420,
  73,
  '00',
  2,
  '01',
  1999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  2000,
  421,
  73,
  '020',
  3,
  '199',
  19999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  20000,
  422,
  73,
  '2000',
  4,
  '3999',
  39999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  40000,
  423,
  73,
  '40000',
  5,
  '44999',
  44999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  45000,
  424,
  73,
  '45',
  2,
  '49',
  49999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  50000,
  425,
  73,
  '50',
  2,
  '79',
  79999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  80000,
  426,
  73,
  '800',
  3,
  '899',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  427,
  73,
  '9000',
  4,
  '9899',
  98999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  99000,
  428,
  73,
  '99000',
  5,
  '99999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  429,
  74,
  '00',
  2,
  '39',
  39999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  40000,
  430,
  74,
  '400',
  3,
  '799',
  79999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  80000,
  431,
  74,
  '8000',
  4,
  '8999',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  432,
  74,
  '90000',
  5,
  '99999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  433,
  75,
  '00',
  2,
  '39',
  39999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  40000,
  434,
  75,
  '400',
  3,
  '599',
  59999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  60000,
  435,
  75,
  '6000',
  4,
  '8999',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  436,
  75,
  '90000',
  5,
  '99999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  437,
  76,
  '00',
  2,
  '11',
  11999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  12000,
  438,
  76,
  '120',
  3,
  '559',
  55999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  56000,
  439,
  76,
  '5600',
  4,
  '7999',
  79999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  80000,
  440,
  76,
  '80000',
  5,
  '99999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  441,
  77,
  '00',
  2,
  '09',
  9999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  10000,
  442,
  77,
  '1000',
  4,
  '1999',
  19999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  20000,
  443,
  77,
  '20000',
  5,
  '29999',
  29999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  30000,
  444,
  77,
  '30',
  2,
  '49',
  49999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  50000,
  445,
  77,
  '500',
  3,
  '899',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  446,
  77,
  '9000',
  4,
  '9499',
  94999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  95000,
  447,
  77,
  '95000',
  5,
  '99999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  448,
  78,
  '00',
  2,
  '14',
  14999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  15000,
  449,
  78,
  '15000',
  5,
  '16999',
  16999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  17000,
  450,
  78,
  '17000',
  5,
  '19999',
  19999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  20000,
  451,
  78,
  '200',
  3,
  '799',
  79999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  80000,
  452,
  78,
  '8000',
  4,
  '9699',
  96999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  97000,
  453,
  78,
  '97000',
  5,
  '99999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  454,
  79,
  '0',
  1,
  '1',
  19999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  20000,
  455,
  79,
  '20',
  2,
  '54',
  54999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  55000,
  456,
  79,
  '550',
  3,
  '799',
  79999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  80000,
  457,
  79,
  '8000',
  4,
  '9499',
  94999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  95000,
  458,
  79,
  '95000',
  5,
  '99999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  459,
  80,
  '00',
  2,
  '09',
  9999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  10000,
  460,
  80,
  '100',
  3,
  '399',
  39999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  40000,
  461,
  80,
  '4000',
  4,
  '4999',
  49999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  462,
  81,
  '00',
  2,
  '09',
  9999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  10000,
  463,
  81,
  '100',
  3,
  '399',
  39999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  40000,
  464,
  81,
  '4000',
  4,
  '4999',
  49999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  465,
  82,
  '0',
  1,
  '3',
  39999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  40000,
  466,
  82,
  '40',
  2,
  '54',
  54999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  55000,
  467,
  82,
  '550',
  3,
  '799',
  79999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  80000,
  468,
  82,
  '8000',
  4,
  '9999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  469,
  83,
  '00',
  2,
  '49',
  49999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  50000,
  470,
  83,
  '500',
  3,
  '939',
  93999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  94000,
  471,
  83,
  '9400',
  4,
  '9999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  472,
  84,
  '00',
  2,
  '29',
  29999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  30000,
  473,
  84,
  '300',
  3,
  '899',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  474,
  84,
  '9000',
  4,
  '9999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  475,
  85,
  '00',
  2,
  '39',
  39999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  40000,
  476,
  85,
  '400',
  3,
  '849',
  84999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  85000,
  477,
  85,
  '8500',
  4,
  '9999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  478,
  86,
  '0',
  1,
  '0',
  9999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  10000,
  479,
  86,
  '10',
  2,
  '39',
  39999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  40000,
  480,
  86,
  '400',
  3,
  '899',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  481,
  86,
  '9000',
  4,
  '9999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  482,
  87,
  '0',
  1,
  '0',
  9999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  10000,
  483,
  87,
  '10',
  2,
  '49',
  49999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  50000,
  484,
  87,
  '500',
  3,
  '799',
  79999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  80000,
  485,
  87,
  '8000',
  4,
  '9999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  486,
  88,
  '0',
  1,
  '0',
  9999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  10000,
  487,
  88,
  '10',
  2,
  '39',
  39999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  40000,
  488,
  88,
  '400',
  3,
  '899',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  489,
  88,
  '9000',
  4,
  '9999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  490,
  89,
  '0',
  1,
  '1',
  19999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  20000,
  491,
  89,
  '20',
  2,
  '39',
  39999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  40000,
  492,
  89,
  '400',
  3,
  '799',
  79999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  80000,
  493,
  89,
  '8000',
  4,
  '9999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  494,
  90,
  '0',
  1,
  '2',
  29999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  30000,
  495,
  90,
  '30',
  2,
  '49',
  49999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  50000,
  496,
  90,
  '500',
  3,
  '799',
  79999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  80000,
  497,
  90,
  '8000',
  4,
  '9999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  498,
  91,
  '00',
  2,
  '79',
  79999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  80000,
  499,
  91,
  '800',
  3,
  '949',
  94999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  95000,
  500,
  91,
  '9500',
  4,
  '9999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  501,
  92,
  '0',
  1,
  '4',
  49999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  50000,
  502,
  92,
  '50',
  2,
  '79',
  79999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  80000,
  503,
  92,
  '800',
  3,
  '899',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  504,
  92,
  '9000',
  4,
  '9999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  505,
  93,
  '0',
  1,
  '1',
  19999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  20000,
  506,
  93,
  '20',
  2,
  '49',
  49999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  50000,
  507,
  93,
  '500',
  3,
  '899',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  508,
  93,
  '9000',
  4,
  '9999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  509,
  94,
  '0',
  1,
  '0',
  9999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  10000,
  510,
  94,
  '10',
  2,
  '39',
  39999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  40000,
  511,
  94,
  '400',
  3,
  '899',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  512,
  94,
  '9000',
  4,
  '9999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  513,
  95,
  '00',
  2,
  '89',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  514,
  95,
  '900',
  3,
  '984',
  98499
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  98500,
  515,
  95,
  '9850',
  4,
  '9999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  516,
  96,
  '00',
  2,
  '29',
  29999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  30000,
  517,
  96,
  '300',
  3,
  '399',
  39999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  40000,
  518,
  96,
  '4000',
  4,
  '9999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  519,
  97,
  '0000',
  4,
  '0999',
  9999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  10000,
  520,
  97,
  '100',
  3,
  '499',
  49999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  50000,
  521,
  97,
  '5000',
  4,
  '5999',
  59999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  60000,
  522,
  97,
  '60',
  2,
  '69',
  69999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  70000,
  523,
  97,
  '700',
  3,
  '799',
  79999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  80000,
  524,
  97,
  '80',
  2,
  '89',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  525,
  97,
  '900',
  3,
  '999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  526,
  98,
  '00',
  2,
  '00',
  999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  1000,
  527,
  98,
  '010',
  3,
  '079',
  7999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  8000,
  528,
  98,
  '08',
  2,
  '39',
  39999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  40000,
  529,
  98,
  '400',
  3,
  '569',
  56999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  57000,
  530,
  98,
  '57',
  2,
  '57',
  57999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  58000,
  531,
  98,
  '580',
  3,
  '849',
  84999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  85000,
  532,
  98,
  '8500',
  4,
  '9999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  533,
  99,
  '0',
  1,
  '1',
  19999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  20000,
  534,
  99,
  '20',
  2,
  '39',
  39999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  40000,
  535,
  99,
  '400',
  3,
  '899',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  536,
  99,
  '9000',
  4,
  '9999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  537,
  100,
  '0',
  1,
  '1',
  19999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  20000,
  538,
  100,
  '20',
  2,
  '79',
  79999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  80000,
  539,
  100,
  '800',
  3,
  '999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  540,
  101,
  '00',
  2,
  '39',
  39999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  40000,
  541,
  101,
  '400',
  3,
  '849',
  84999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  85000,
  542,
  101,
  '8500',
  4,
  '9999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  543,
  102,
  '0',
  1,
  '0',
  9999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  10000,
  544,
  102,
  '10',
  2,
  '39',
  39999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  40000,
  545,
  102,
  '400',
  3,
  '899',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  546,
  102,
  '9000',
  4,
  '9999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  547,
  103,
  '00',
  2,
  '29',
  29999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  30000,
  548,
  103,
  '300',
  3,
  '849',
  84999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  85000,
  549,
  103,
  '8500',
  4,
  '9999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  550,
  104,
  '00',
  2,
  '39',
  39999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  40000,
  551,
  104,
  '400',
  3,
  '849',
  84999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  85000,
  552,
  104,
  '8500',
  4,
  '9999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  553,
  105,
  '0',
  1,
  '1',
  19999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  20000,
  554,
  105,
  '20',
  2,
  '39',
  39999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  40000,
  555,
  105,
  '400',
  3,
  '799',
  79999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  80000,
  556,
  105,
  '8000',
  4,
  '9999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  557,
  106,
  '0',
  1,
  '0',
  9999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  10000,
  558,
  106,
  '10',
  2,
  '39',
  39999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  40000,
  559,
  106,
  '400',
  3,
  '599',
  59999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  60000,
  560,
  106,
  '60',
  2,
  '89',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  561,
  106,
  '9000',
  4,
  '9999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  562,
  107,
  '0',
  1,
  '1',
  19999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  20000,
  563,
  107,
  '20',
  2,
  '39',
  39999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  40000,
  564,
  107,
  '400',
  3,
  '799',
  79999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  80000,
  565,
  107,
  '8000',
  4,
  '9999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  566,
  108,
  '00',
  2,
  '39',
  39999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  40000,
  567,
  108,
  '400',
  3,
  '929',
  92999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  93000,
  568,
  108,
  '9300',
  4,
  '9999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  569,
  109,
  '0',
  1,
  '0',
  9999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  10000,
  570,
  109,
  '10',
  2,
  '39',
  39999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  40000,
  571,
  109,
  '400',
  3,
  '899',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  572,
  109,
  '9000',
  4,
  '9999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  573,
  110,
  '00',
  2,
  '39',
  39999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  40000,
  574,
  110,
  '400',
  3,
  '699',
  69999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  70000,
  575,
  110,
  '70',
  2,
  '84',
  84999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  85000,
  576,
  110,
  '8500',
  4,
  '8799',
  87999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  88000,
  577,
  110,
  '88',
  2,
  '99',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  578,
  111,
  '0',
  1,
  '0',
  9999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  10000,
  579,
  111,
  '10',
  2,
  '18',
  18999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  19000,
  580,
  111,
  '1900',
  4,
  '1999',
  19999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  20000,
  581,
  111,
  '20',
  2,
  '49',
  49999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  50000,
  582,
  111,
  '500',
  3,
  '899',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  583,
  111,
  '9000',
  4,
  '9999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  584,
  112,
  '0',
  1,
  '1',
  19999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  20000,
  585,
  112,
  '20',
  2,
  '79',
  79999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  80000,
  586,
  112,
  '800',
  3,
  '949',
  94999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  95000,
  587,
  112,
  '9500',
  4,
  '9999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  588,
  113,
  '00',
  2,
  '59',
  59999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  60000,
  589,
  113,
  '600',
  3,
  '899',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  590,
  113,
  '9000',
  4,
  '9999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  591,
  114,
  '0',
  1,
  '2',
  29999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  30000,
  592,
  114,
  '30',
  2,
  '69',
  69999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  70000,
  593,
  114,
  '700',
  3,
  '949',
  94999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  95000,
  594,
  114,
  '9500',
  4,
  '9999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  595,
  115,
  '00',
  2,
  '54',
  54999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  55000,
  596,
  115,
  '5500',
  4,
  '5599',
  55999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  56000,
  597,
  115,
  '56',
  2,
  '59',
  59999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  60000,
  598,
  115,
  '600',
  3,
  '849',
  84999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  85000,
  599,
  115,
  '8500',
  4,
  '9999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  600,
  116,
  '0',
  1,
  '2',
  29999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  30000,
  601,
  116,
  '30',
  2,
  '54',
  54999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  55000,
  602,
  116,
  '550',
  3,
  '734',
  73499
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  73500,
  603,
  116,
  '7350',
  4,
  '7499',
  74999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  75000,
  604,
  116,
  '7500',
  4,
  '9999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  605,
  117,
  '0',
  1,
  '6',
  69999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  70000,
  606,
  117,
  '70',
  2,
  '94',
  94999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  95000,
  607,
  117,
  '950',
  3,
  '999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  608,
  118,
  '00',
  2,
  '39',
  39999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  40000,
  609,
  118,
  '400',
  3,
  '899',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  610,
  118,
  '9000',
  4,
  '9999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  611,
  119,
  '000',
  3,
  '149',
  14999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  15000,
  612,
  119,
  '1500',
  4,
  '1999',
  19999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  20000,
  613,
  119,
  '20',
  2,
  '69',
  69999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  70000,
  614,
  119,
  '7000',
  4,
  '7499',
  74999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  75000,
  615,
  119,
  '750',
  3,
  '959',
  95999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  96000,
  616,
  119,
  '9600',
  4,
  '9999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  617,
  120,
  '00',
  2,
  '39',
  39999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  40000,
  618,
  120,
  '400',
  3,
  '899',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  619,
  120,
  '9000',
  4,
  '9999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  620,
  121,
  '00',
  2,
  '49',
  49999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  50000,
  621,
  121,
  '500',
  3,
  '939',
  93999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  94000,
  622,
  121,
  '9400',
  4,
  '9999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  623,
  122,
  '00',
  2,
  '39',
  39999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  40000,
  624,
  122,
  '400',
  3,
  '899',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  625,
  122,
  '9000',
  4,
  '9999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  626,
  123,
  '0',
  1,
  '5',
  59999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  60000,
  627,
  123,
  '60',
  2,
  '89',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  628,
  123,
  '900',
  3,
  '989',
  98999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  99000,
  629,
  123,
  '9900',
  4,
  '9999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  630,
  124,
  '00',
  2,
  '09',
  9999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  10000,
  631,
  124,
  '1',
  1,
  '1',
  19999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  20000,
  632,
  124,
  '200',
  3,
  '249',
  24999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  25000,
  633,
  124,
  '2500',
  4,
  '2999',
  29999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  30000,
  634,
  124,
  '30',
  2,
  '59',
  59999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  60000,
  635,
  124,
  '600',
  3,
  '899',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  636,
  124,
  '9000',
  4,
  '9999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  637,
  125,
  '00',
  2,
  '05',
  5999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  6000,
  638,
  125,
  '060',
  3,
  '089',
  8999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  9000,
  639,
  125,
  '0900',
  4,
  '0999',
  9999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  10000,
  640,
  125,
  '10',
  2,
  '69',
  69999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  70000,
  641,
  125,
  '700',
  3,
  '969',
  96999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  97000,
  642,
  125,
  '9700',
  4,
  '9999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  643,
  126,
  '0',
  1,
  '2',
  29999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  30000,
  644,
  126,
  '30',
  2,
  '54',
  54999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  55000,
  645,
  126,
  '550',
  3,
  '749',
  74999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  75000,
  646,
  126,
  '7500',
  4,
  '9499',
  94999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  95000,
  647,
  126,
  '95',
  2,
  '99',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  648,
  127,
  '0',
  1,
  '0',
  9999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  10000,
  649,
  127,
  '100',
  3,
  '399',
  39999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  40000,
  650,
  127,
  '4000',
  4,
  '4499',
  44999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  45000,
  651,
  127,
  '45',
  2,
  '89',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  652,
  127,
  '900',
  3,
  '949',
  94999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  95000,
  653,
  127,
  '9500',
  4,
  '9999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  654,
  128,
  '0',
  1,
  '5',
  59999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  60000,
  655,
  128,
  '60',
  2,
  '89',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  656,
  128,
  '900',
  3,
  '989',
  98999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  99000,
  657,
  128,
  '9900',
  4,
  '9999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  658,
  129,
  '00',
  2,
  '89',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  659,
  129,
  '900',
  3,
  '989',
  98999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  99000,
  660,
  129,
  '9900',
  4,
  '9999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  661,
  130,
  '00',
  2,
  '29',
  29999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  30000,
  662,
  130,
  '300',
  3,
  '399',
  39999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  40000,
  663,
  130,
  '40',
  2,
  '94',
  94999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  95000,
  664,
  130,
  '950',
  3,
  '989',
  98999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  99000,
  665,
  130,
  '9900',
  4,
  '9999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  666,
  131,
  '0',
  1,
  '4',
  49999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  50000,
  667,
  131,
  '50',
  2,
  '64',
  64999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  65000,
  668,
  131,
  '650',
  3,
  '659',
  65999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  66000,
  669,
  131,
  '66',
  2,
  '75',
  75999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  76000,
  670,
  131,
  '760',
  3,
  '899',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  671,
  131,
  '9000',
  4,
  '9999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  672,
  132,
  '0',
  1,
  '3',
  39999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  40000,
  673,
  132,
  '40',
  2,
  '89',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  674,
  132,
  '900',
  3,
  '989',
  98999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  99000,
  675,
  132,
  '9900',
  4,
  '9999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  676,
  133,
  '00',
  2,
  '09',
  9999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  10000,
  677,
  133,
  '100',
  3,
  '159',
  15999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  16000,
  678,
  133,
  '1600',
  4,
  '1999',
  19999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  20000,
  679,
  133,
  '20',
  2,
  '79',
  79999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  80000,
  680,
  133,
  '800',
  3,
  '949',
  94999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  95000,
  681,
  133,
  '9500',
  4,
  '9999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  682,
  134,
  '00',
  2,
  '79',
  79999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  80000,
  683,
  134,
  '800',
  3,
  '989',
  98999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  99000,
  684,
  134,
  '9900',
  4,
  '9999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  80000,
  685,
  135,
  '80',
  2,
  '94',
  94999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  95000,
  686,
  135,
  '950',
  3,
  '989',
  98999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  99000,
  687,
  135,
  '9900',
  4,
  '9999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  688,
  136,
  '00',
  2,
  '49',
  49999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  50000,
  689,
  136,
  '500',
  3,
  '899',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  690,
  136,
  '9000',
  4,
  '9999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  691,
  137,
  '0',
  1,
  '4',
  49999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  50000,
  692,
  137,
  '50',
  2,
  '79',
  79999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  80000,
  693,
  137,
  '800',
  3,
  '899',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  694,
  137,
  '9000',
  4,
  '9999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  695,
  138,
  '00',
  2,
  '39',
  39999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  40000,
  696,
  138,
  '400',
  3,
  '899',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  697,
  138,
  '9000',
  4,
  '9399',
  93999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  94000,
  698,
  138,
  '940',
  3,
  '969',
  96999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  97000,
  699,
  138,
  '97',
  2,
  '99',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  700,
  139,
  '00',
  2,
  '39',
  39999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  40000,
  701,
  139,
  '400',
  3,
  '879',
  87999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  88000,
  702,
  139,
  '8800',
  4,
  '9999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  703,
  140,
  '0',
  1,
  '2',
  29999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  30000,
  704,
  140,
  '30',
  2,
  '54',
  54999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  55000,
  705,
  140,
  '550',
  3,
  '749',
  74999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  75000,
  706,
  140,
  '7500',
  4,
  '9999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  707,
  141,
  '0',
  1,
  '0',
  9999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  10000,
  708,
  141,
  '100',
  3,
  '199',
  19999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  20000,
  709,
  141,
  '2000',
  4,
  '2999',
  29999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  30000,
  710,
  141,
  '30',
  2,
  '59',
  59999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  60000,
  711,
  141,
  '600',
  3,
  '949',
  94999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  95000,
  712,
  141,
  '9500',
  4,
  '9999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  713,
  142,
  '00',
  2,
  '49',
  49999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  50000,
  714,
  142,
  '500',
  3,
  '799',
  79999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  80000,
  715,
  142,
  '80',
  2,
  '99',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  716,
  144,
  '0',
  1,
  '1',
  19999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  20000,
  717,
  144,
  '20',
  2,
  '89',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  718,
  144,
  '900',
  3,
  '999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  719,
  145,
  '0',
  1,
  '5',
  59999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  60000,
  720,
  145,
  '60',
  2,
  '89',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  721,
  145,
  '900',
  3,
  '999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  722,
  146,
  '0',
  1,
  '3',
  39999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  40000,
  723,
  146,
  '40',
  2,
  '79',
  79999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  80000,
  724,
  146,
  '800',
  3,
  '999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  725,
  147,
  '0',
  1,
  '2',
  29999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  30000,
  726,
  147,
  '30',
  2,
  '59',
  59999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  60000,
  727,
  147,
  '600',
  3,
  '699',
  69999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  70000,
  728,
  147,
  '70',
  2,
  '89',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  729,
  147,
  '90',
  2,
  '94',
  94999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  95000,
  730,
  147,
  '950',
  3,
  '999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  731,
  148,
  '0',
  1,
  '0',
  9999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  10000,
  732,
  148,
  '10',
  2,
  '89',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  733,
  148,
  '900',
  3,
  '999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  734,
  149,
  '0',
  1,
  '3',
  39999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  40000,
  735,
  149,
  '40',
  2,
  '94',
  94999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  95000,
  736,
  149,
  '950',
  3,
  '999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  737,
  150,
  '0',
  1,
  '2',
  29999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  30000,
  738,
  150,
  '30',
  2,
  '89',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  739,
  150,
  '900',
  3,
  '999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  740,
  151,
  '00',
  2,
  '59',
  59999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  60000,
  741,
  151,
  '600',
  3,
  '999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  742,
  152,
  '0',
  1,
  '3',
  39999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  40000,
  743,
  152,
  '400',
  3,
  '599',
  59999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  60000,
  744,
  152,
  '60',
  2,
  '89',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  745,
  152,
  '900',
  3,
  '999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  746,
  153,
  '0',
  1,
  '2',
  29999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  30000,
  747,
  153,
  '30',
  2,
  '35',
  35999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  60000,
  748,
  153,
  '600',
  3,
  '604',
  60499
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  749,
  154,
  '0',
  1,
  '4',
  49999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  50000,
  750,
  154,
  '50',
  2,
  '89',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  751,
  154,
  '900',
  3,
  '999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  752,
  155,
  '0',
  1,
  '4',
  49999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  50000,
  753,
  155,
  '50',
  2,
  '79',
  79999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  80000,
  754,
  155,
  '800',
  3,
  '999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  755,
  156,
  '0',
  1,
  '2',
  29999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  30000,
  756,
  156,
  '30',
  2,
  '69',
  69999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  70000,
  757,
  156,
  '700',
  3,
  '999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  758,
  157,
  '0',
  1,
  '2',
  29999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  30000,
  759,
  157,
  '30',
  2,
  '89',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  760,
  157,
  '900',
  3,
  '999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  761,
  158,
  '0',
  1,
  '3',
  39999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  40000,
  762,
  158,
  '40',
  2,
  '79',
  79999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  80000,
  763,
  158,
  '800',
  3,
  '999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  764,
  159,
  '0',
  1,
  '2',
  29999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  30000,
  765,
  159,
  '300',
  3,
  '399',
  39999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  40000,
  766,
  159,
  '40',
  2,
  '69',
  69999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  767,
  159,
  '900',
  3,
  '999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  768,
  160,
  '0',
  1,
  '4',
  49999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  50000,
  769,
  160,
  '50',
  2,
  '89',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  770,
  160,
  '900',
  3,
  '999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  771,
  161,
  '0',
  1,
  '1',
  19999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  20000,
  772,
  161,
  '20',
  2,
  '69',
  69999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  70000,
  773,
  161,
  '700',
  3,
  '799',
  79999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  80000,
  774,
  161,
  '8',
  1,
  '8',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  775,
  161,
  '90',
  2,
  '99',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  776,
  162,
  '0',
  1,
  '3',
  39999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  40000,
  777,
  162,
  '40',
  2,
  '69',
  69999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  70000,
  778,
  162,
  '700',
  3,
  '999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  779,
  163,
  '0',
  1,
  '1',
  19999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  20000,
  780,
  163,
  '20',
  2,
  '79',
  79999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  80000,
  781,
  163,
  '800',
  3,
  '999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  782,
  164,
  '0',
  1,
  '1',
  19999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  20000,
  783,
  164,
  '20',
  2,
  '79',
  79999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  80000,
  784,
  164,
  '800',
  3,
  '999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  785,
  165,
  '0',
  1,
  '3',
  39999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  40000,
  786,
  165,
  '40',
  2,
  '79',
  79999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  80000,
  787,
  165,
  '800',
  3,
  '999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  788,
  166,
  '0',
  1,
  '0',
  9999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  10000,
  789,
  166,
  '10',
  2,
  '59',
  59999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  60000,
  790,
  166,
  '600',
  3,
  '999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  791,
  167,
  '0',
  1,
  '2',
  29999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  30000,
  792,
  167,
  '30',
  2,
  '59',
  59999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  60000,
  793,
  167,
  '600',
  3,
  '999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  794,
  168,
  '0',
  1,
  '0',
  9999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  10000,
  795,
  168,
  '10',
  2,
  '79',
  79999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  80000,
  796,
  168,
  '800',
  3,
  '999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  797,
  169,
  '0',
  1,
  '4',
  49999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  50000,
  798,
  169,
  '50',
  2,
  '79',
  79999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  80000,
  799,
  169,
  '800',
  3,
  '999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  800,
  170,
  '0',
  1,
  '4',
  49999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  50000,
  801,
  170,
  '50',
  2,
  '79',
  79999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  80000,
  802,
  170,
  '800',
  3,
  '999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  803,
  171,
  '0',
  1,
  '4',
  49999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  50000,
  804,
  171,
  '50',
  2,
  '79',
  79999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  80000,
  805,
  171,
  '800',
  3,
  '999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  806,
  172,
  '0',
  1,
  '0',
  9999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  10000,
  807,
  172,
  '10',
  2,
  '59',
  59999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  60000,
  808,
  172,
  '600',
  3,
  '699',
  69999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  70000,
  809,
  172,
  '7',
  1,
  '7',
  79999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  80000,
  810,
  172,
  '80',
  2,
  '99',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  811,
  173,
  '0',
  1,
  '2',
  29999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  30000,
  812,
  173,
  '30',
  2,
  '59',
  59999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  60000,
  813,
  173,
  '600',
  3,
  '999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  814,
  174,
  '0',
  1,
  '1',
  19999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  20000,
  815,
  174,
  '20',
  2,
  '79',
  79999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  80000,
  816,
  174,
  '800',
  3,
  '999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  817,
  175,
  '0',
  1,
  '2',
  29999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  30000,
  818,
  175,
  '30',
  2,
  '59',
  59999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  60000,
  819,
  175,
  '600',
  3,
  '699',
  69999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  70000,
  820,
  175,
  '7',
  1,
  '8',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  821,
  175,
  '90',
  2,
  '99',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  822,
  176,
  '0',
  1,
  '0',
  9999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  10000,
  823,
  176,
  '10',
  2,
  '59',
  59999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  60000,
  824,
  176,
  '600',
  3,
  '999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  825,
  177,
  '0',
  1,
  '1',
  19999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  20000,
  826,
  177,
  '20',
  2,
  '59',
  59999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  60000,
  827,
  177,
  '600',
  3,
  '999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  828,
  178,
  '0',
  1,
  '1',
  19999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  20000,
  829,
  178,
  '20',
  2,
  '59',
  59999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  60000,
  830,
  178,
  '600',
  3,
  '899',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  831,
  178,
  '90',
  2,
  '99',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  832,
  179,
  '0',
  1,
  '5',
  59999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  60000,
  833,
  179,
  '60',
  2,
  '89',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  834,
  179,
  '900',
  3,
  '999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  835,
  180,
  '0',
  1,
  '0',
  9999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  10000,
  836,
  180,
  '10',
  2,
  '69',
  69999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  70000,
  837,
  180,
  '700',
  3,
  '999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  838,
  181,
  '0',
  1,
  '2',
  29999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  30000,
  839,
  181,
  '30',
  2,
  '79',
  79999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  80000,
  840,
  181,
  '800',
  3,
  '999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  841,
  182,
  '0',
  1,
  '4',
  49999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  50000,
  842,
  182,
  '50',
  2,
  '79',
  79999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  80000,
  843,
  182,
  '800',
  3,
  '999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  844,
  183,
  '0',
  1,
  '2',
  29999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  30000,
  845,
  183,
  '30',
  2,
  '59',
  59999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  60000,
  846,
  183,
  '600',
  3,
  '999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  847,
  184,
  '0',
  1,
  '4',
  49999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  50000,
  848,
  184,
  '50',
  2,
  '79',
  79999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  80000,
  849,
  184,
  '800',
  3,
  '999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  850,
  185,
  '0',
  1,
  '5',
  59999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  60000,
  851,
  185,
  '60',
  2,
  '89',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  852,
  185,
  '900',
  3,
  '999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  853,
  186,
  '0',
  1,
  '2',
  29999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  30000,
  854,
  186,
  '30',
  2,
  '59',
  59999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  60000,
  855,
  186,
  '600',
  3,
  '999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  856,
  187,
  '0',
  1,
  '2',
  29999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  30000,
  857,
  187,
  '30',
  2,
  '69',
  69999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  70000,
  858,
  187,
  '700',
  3,
  '999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  859,
  188,
  '0',
  1,
  '4',
  49999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  50000,
  860,
  188,
  '50',
  2,
  '79',
  79999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  80000,
  861,
  188,
  '800',
  3,
  '999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  862,
  189,
  '0',
  1,
  '1',
  19999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  20000,
  863,
  189,
  '20',
  2,
  '89',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  864,
  189,
  '900',
  3,
  '999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  865,
  190,
  '0',
  1,
  '4',
  49999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  50000,
  866,
  190,
  '50',
  2,
  '79',
  79999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  80000,
  867,
  190,
  '800',
  3,
  '999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  868,
  192,
  '0',
  1,
  '4',
  49999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  50000,
  869,
  192,
  '50',
  2,
  '79',
  79999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  80000,
  870,
  192,
  '800',
  3,
  '999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  871,
  193,
  '0',
  1,
  '2',
  29999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  30000,
  872,
  193,
  '30',
  2,
  '79',
  79999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  80000,
  873,
  193,
  '800',
  3,
  '939',
  93999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  94000,
  874,
  193,
  '94',
  2,
  '99',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  875,
  194,
  '0',
  1,
  '2',
  29999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  30000,
  876,
  194,
  '30',
  2,
  '69',
  69999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  70000,
  877,
  194,
  '700',
  3,
  '999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  878,
  195,
  '0',
  1,
  '1',
  19999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  20000,
  879,
  195,
  '20',
  2,
  '59',
  59999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  60000,
  880,
  195,
  '600',
  3,
  '799',
  79999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  80000,
  881,
  195,
  '80',
  2,
  '89',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  882,
  195,
  '90',
  2,
  '99',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  883,
  196,
  '00',
  2,
  '59',
  59999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  60000,
  884,
  196,
  '600',
  3,
  '859',
  85999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  86000,
  885,
  196,
  '86',
  2,
  '99',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  886,
  197,
  '0',
  1,
  '1',
  19999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  20000,
  887,
  197,
  '20',
  2,
  '79',
  79999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  80000,
  888,
  197,
  '800',
  3,
  '999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  889,
  198,
  '0',
  1,
  '4',
  49999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  50000,
  890,
  198,
  '50',
  2,
  '94',
  94999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  95000,
  891,
  198,
  '950',
  3,
  '999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  892,
  199,
  '0',
  1,
  '2',
  29999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  30000,
  893,
  199,
  '30',
  2,
  '59',
  59999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  60000,
  894,
  199,
  '600',
  3,
  '999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  895,
  200,
  '0',
  1,
  '0',
  9999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  10000,
  896,
  200,
  '10',
  2,
  '94',
  94999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  95000,
  897,
  200,
  '950',
  3,
  '999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  898,
  201,
  '0',
  1,
  '3',
  39999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  40000,
  899,
  201,
  '40',
  2,
  '89',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  900,
  201,
  '900',
  3,
  '999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  901,
  202,
  '0',
  1,
  '4',
  49999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  50000,
  902,
  202,
  '50',
  2,
  '79',
  79999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  80000,
  903,
  202,
  '800',
  3,
  '999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  904,
  203,
  '00',
  2,
  '49',
  49999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  50000,
  905,
  203,
  '500',
  3,
  '999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  906,
  204,
  '0',
  1,
  '1',
  19999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  20000,
  907,
  204,
  '20',
  2,
  '79',
  79999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  80000,
  908,
  204,
  '800',
  3,
  '999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  909,
  205,
  '0',
  1,
  '3',
  39999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  40000,
  910,
  205,
  '40',
  2,
  '79',
  79999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  80000,
  911,
  205,
  '800',
  3,
  '999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  912,
  206,
  '0',
  1,
  '2',
  29999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  30000,
  913,
  206,
  '30',
  2,
  '69',
  69999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  70000,
  914,
  206,
  '700',
  3,
  '799',
  79999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  915,
  207,
  '0',
  1,
  '1',
  19999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  20000,
  916,
  207,
  '20',
  2,
  '59',
  59999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  60000,
  917,
  207,
  '600',
  3,
  '899',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  918,
  208,
  '0',
  1,
  '3',
  39999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  40000,
  919,
  208,
  '400',
  3,
  '599',
  59999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  60000,
  920,
  208,
  '60',
  2,
  '89',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  921,
  208,
  '900',
  3,
  '999',
  99999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  0,
  922,
  209,
  '00',
  2,
  '19',
  19999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  20000,
  923,
  209,
  '200',
  3,
  '699',
  69999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  70000,
  924,
  209,
  '7000',
  4,
  '8999',
  89999
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  90000,
  925,
  209,
  '90000',
  5,
  '97599',
  97599
);
INSERT INTO ezisbn_registrant_range (
  from_number,
  id,
  isbn_group_id,
  registrant_from,
  registrant_length,
  registrant_to,
  to_number
) VALUES (
  97600,
  926,
  209,
  '976000',
  6,
  '999999',
  99999
);

INSERT INTO eznode_assignment (
  contentobject_id,
  contentobject_version,
  from_node_id,
  id,
  is_main,
  op_code,
  parent_node,
  parent_remote_id,
  remote_id,
  sort_field,
  sort_order
) VALUES (
  8,
  2,
  0,
  4,
  1,
  2,
  5,
  '',
  0,
  1,
  1
);
INSERT INTO eznode_assignment (
  contentobject_id,
  contentobject_version,
  from_node_id,
  id,
  is_main,
  op_code,
  parent_node,
  parent_remote_id,
  remote_id,
  sort_field,
  sort_order
) VALUES (
  42,
  1,
  0,
  5,
  1,
  2,
  5,
  '',
  0,
  9,
  1
);
INSERT INTO eznode_assignment (
  contentobject_id,
  contentobject_version,
  from_node_id,
  id,
  is_main,
  op_code,
  parent_node,
  parent_remote_id,
  remote_id,
  sort_field,
  sort_order
) VALUES (
  10,
  2,
  -1,
  6,
  1,
  2,
  44,
  '',
  0,
  9,
  1
);
INSERT INTO eznode_assignment (
  contentobject_id,
  contentobject_version,
  from_node_id,
  id,
  is_main,
  op_code,
  parent_node,
  parent_remote_id,
  remote_id,
  sort_field,
  sort_order
) VALUES (
  4,
  1,
  0,
  7,
  1,
  2,
  1,
  '',
  0,
  1,
  1
);
INSERT INTO eznode_assignment (
  contentobject_id,
  contentobject_version,
  from_node_id,
  id,
  is_main,
  op_code,
  parent_node,
  parent_remote_id,
  remote_id,
  sort_field,
  sort_order
) VALUES (
  12,
  1,
  0,
  8,
  1,
  2,
  5,
  '',
  0,
  1,
  1
);
INSERT INTO eznode_assignment (
  contentobject_id,
  contentobject_version,
  from_node_id,
  id,
  is_main,
  op_code,
  parent_node,
  parent_remote_id,
  remote_id,
  sort_field,
  sort_order
) VALUES (
  13,
  1,
  0,
  9,
  1,
  2,
  5,
  '',
  0,
  1,
  1
);
INSERT INTO eznode_assignment (
  contentobject_id,
  contentobject_version,
  from_node_id,
  id,
  is_main,
  op_code,
  parent_node,
  parent_remote_id,
  remote_id,
  sort_field,
  sort_order
) VALUES (
  41,
  1,
  0,
  11,
  1,
  2,
  1,
  '',
  0,
  1,
  1
);
INSERT INTO eznode_assignment (
  contentobject_id,
  contentobject_version,
  from_node_id,
  id,
  is_main,
  op_code,
  parent_node,
  parent_remote_id,
  remote_id,
  sort_field,
  sort_order
) VALUES (
  11,
  1,
  0,
  12,
  1,
  2,
  5,
  '',
  0,
  1,
  1
);
INSERT INTO eznode_assignment (
  contentobject_id,
  contentobject_version,
  from_node_id,
  id,
  is_main,
  op_code,
  parent_node,
  parent_remote_id,
  remote_id,
  sort_field,
  sort_order
) VALUES (
  45,
  1,
  -1,
  16,
  1,
  2,
  1,
  '',
  0,
  9,
  1
);
INSERT INTO eznode_assignment (
  contentobject_id,
  contentobject_version,
  from_node_id,
  id,
  is_main,
  op_code,
  parent_node,
  parent_remote_id,
  remote_id,
  sort_field,
  sort_order
) VALUES (
  49,
  1,
  0,
  27,
  1,
  2,
  43,
  '',
  0,
  9,
  1
);
INSERT INTO eznode_assignment (
  contentobject_id,
  contentobject_version,
  from_node_id,
  id,
  is_main,
  op_code,
  parent_node,
  parent_remote_id,
  remote_id,
  sort_field,
  sort_order
) VALUES (
  50,
  1,
  0,
  28,
  1,
  2,
  43,
  '',
  0,
  9,
  1
);
INSERT INTO eznode_assignment (
  contentobject_id,
  contentobject_version,
  from_node_id,
  id,
  is_main,
  op_code,
  parent_node,
  parent_remote_id,
  remote_id,
  sort_field,
  sort_order
) VALUES (
  51,
  1,
  0,
  29,
  1,
  2,
  43,
  '',
  0,
  9,
  1
);
INSERT INTO eznode_assignment (
  contentobject_id,
  contentobject_version,
  from_node_id,
  id,
  is_main,
  op_code,
  parent_node,
  parent_remote_id,
  remote_id,
  sort_field,
  sort_order
) VALUES (
  52,
  1,
  0,
  30,
  1,
  2,
  48,
  '',
  0,
  1,
  1
);
INSERT INTO eznode_assignment (
  contentobject_id,
  contentobject_version,
  from_node_id,
  id,
  is_main,
  op_code,
  parent_node,
  parent_remote_id,
  remote_id,
  sort_field,
  sort_order
) VALUES (
  56,
  1,
  0,
  34,
  1,
  2,
  1,
  '',
  0,
  2,
  0
);
INSERT INTO eznode_assignment (
  contentobject_id,
  contentobject_version,
  from_node_id,
  id,
  is_main,
  op_code,
  parent_node,
  parent_remote_id,
  remote_id,
  sort_field,
  sort_order
) VALUES (
  14,
  3,
  -1,
  38,
  1,
  2,
  13,
  '',
  0,
  1,
  1
);
INSERT INTO eznode_assignment (
  contentobject_id,
  contentobject_version,
  from_node_id,
  id,
  is_main,
  op_code,
  parent_node,
  parent_remote_id,
  remote_id,
  sort_field,
  sort_order
) VALUES (
  54,
  2,
  -1,
  39,
  1,
  2,
  58,
  '',
  0,
  1,
  1
);
INSERT INTO eznode_assignment (
  contentobject_id,
  contentobject_version,
  from_node_id,
  id,
  is_main,
  op_code,
  parent_node,
  parent_remote_id,
  remote_id,
  sort_field,
  sort_order
) VALUES (
  1,
  6,
  -1,
  42,
  1,
  2,
  1,
  '',
  0,
  8,
  1
);

INSERT INTO ezorder_status (
  id,
  is_active,
  name,
  status_id
) VALUES (
  1,
  1,
  'Pending',
  1
);
INSERT INTO ezorder_status (
  id,
  is_active,
  name,
  status_id
) VALUES (
  2,
  1,
  'Processing',
  2
);
INSERT INTO ezorder_status (
  id,
  is_active,
  name,
  status_id
) VALUES (
  3,
  1,
  'Delivered',
  3
);

INSERT INTO ezpackage (
  id,
  install_date,
  name,
  version
) VALUES (
  1,
  1301057838,
  'plain_site_data',
  '1.0-1'
);

INSERT INTO ezpolicy (
  function_name,
  id,
  module_name,
  original_id,
  role_id
) VALUES (
  '*',
  308,
  '*',
  0,
  2
);
INSERT INTO ezpolicy (
  function_name,
  id,
  module_name,
  original_id,
  role_id
) VALUES (
  '*',
  317,
  'content',
  0,
  3
);
INSERT INTO ezpolicy (
  function_name,
  id,
  module_name,
  original_id,
  role_id
) VALUES (
  'login',
  319,
  'user',
  0,
  3
);
INSERT INTO ezpolicy (
  function_name,
  id,
  module_name,
  original_id,
  role_id
) VALUES (
  'read',
  328,
  'content',
  0,
  1
);
INSERT INTO ezpolicy (
  function_name,
  id,
  module_name,
  original_id,
  role_id
) VALUES (
  'pdf',
  329,
  'content',
  0,
  1
);
INSERT INTO ezpolicy (
  function_name,
  id,
  module_name,
  original_id,
  role_id
) VALUES (
  '*',
  330,
  'ezoe',
  0,
  3
);
INSERT INTO ezpolicy (
  function_name,
  id,
  module_name,
  original_id,
  role_id
) VALUES (
  'login',
  331,
  'user',
  0,
  1
);

INSERT INTO ezpolicy_limitation (
  id,
  identifier,
  policy_id
) VALUES (
  251,
  'Section',
  328
);
INSERT INTO ezpolicy_limitation (
  id,
  identifier,
  policy_id
) VALUES (
  252,
  'Section',
  329
);
INSERT INTO ezpolicy_limitation (
  id,
  identifier,
  policy_id
) VALUES (
  253,
  'SiteAccess',
  331
);

INSERT INTO ezpolicy_limitation_value (
  id,
  limitation_id,
  value
) VALUES (
  477,
  251,
  '1'
);
INSERT INTO ezpolicy_limitation_value (
  id,
  limitation_id,
  value
) VALUES (
  478,
  252,
  '1'
);
INSERT INTO ezpolicy_limitation_value (
  id,
  limitation_id,
  value
) VALUES (
  479,
  253,
  '1833031301'
);

INSERT INTO ezrole (
  id,
  is_new,
  name,
  value,
  version
) VALUES (
  1,
  0,
  'Anonymous',
  ' ',
  0
);
INSERT INTO ezrole (
  id,
  is_new,
  name,
  value,
  version
) VALUES (
  2,
  0,
  'Administrator',
  '*',
  0
);
INSERT INTO ezrole (
  id,
  is_new,
  name,
  value,
  version
) VALUES (
  3,
  0,
  'Editor',
  ' ',
  0
);

INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  4,
  1,
  1,
  0,
  4381,
  'name',
  0,
  801,
  0,
  0,
  1033917596,
  1,
  800
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  4,
  1,
  1,
  0,
  4382,
  'name',
  0,
  802,
  1,
  800,
  1033917596,
  1,
  801
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  4,
  1,
  1,
  0,
  4383,
  'name',
  0,
  803,
  2,
  801,
  1033917596,
  1,
  802
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  4,
  1,
  1,
  0,
  4384,
  'name',
  0,
  802,
  3,
  802,
  1033917596,
  1,
  803
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  155,
  1,
  1,
  0,
  4385,
  'short_name',
  0,
  803,
  4,
  803,
  1033917596,
  1,
  802
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  155,
  1,
  1,
  0,
  4386,
  'short_name',
  0,
  804,
  5,
  802,
  1033917596,
  1,
  803
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  119,
  1,
  1,
  0,
  4387,
  'short_description',
  0,
  805,
  6,
  803,
  1033917596,
  1,
  804
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  119,
  1,
  1,
  0,
  4388,
  'short_description',
  0,
  802,
  7,
  804,
  1033917596,
  1,
  805
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  119,
  1,
  1,
  0,
  4389,
  'short_description',
  0,
  806,
  8,
  805,
  1033917596,
  1,
  802
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  119,
  1,
  1,
  0,
  4390,
  'short_description',
  0,
  807,
  9,
  802,
  1033917596,
  1,
  806
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  119,
  1,
  1,
  0,
  4391,
  'short_description',
  0,
  808,
  10,
  806,
  1033917596,
  1,
  807
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  119,
  1,
  1,
  0,
  4392,
  'short_description',
  0,
  809,
  11,
  807,
  1033917596,
  1,
  808
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  119,
  1,
  1,
  0,
  4393,
  'short_description',
  0,
  810,
  12,
  808,
  1033917596,
  1,
  809
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  119,
  1,
  1,
  0,
  4394,
  'short_description',
  0,
  811,
  13,
  809,
  1033917596,
  1,
  810
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  119,
  1,
  1,
  0,
  4395,
  'short_description',
  0,
  812,
  14,
  810,
  1033917596,
  1,
  811
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  119,
  1,
  1,
  0,
  4396,
  'short_description',
  0,
  813,
  15,
  811,
  1033917596,
  1,
  812
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  119,
  1,
  1,
  0,
  4397,
  'short_description',
  0,
  814,
  16,
  812,
  1033917596,
  1,
  813
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  119,
  1,
  1,
  0,
  4398,
  'short_description',
  0,
  802,
  17,
  813,
  1033917596,
  1,
  814
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  119,
  1,
  1,
  0,
  4399,
  'short_description',
  0,
  803,
  18,
  814,
  1033917596,
  1,
  802
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  119,
  1,
  1,
  0,
  4400,
  'short_description',
  0,
  815,
  19,
  802,
  1033917596,
  1,
  803
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  119,
  1,
  1,
  0,
  4401,
  'short_description',
  0,
  816,
  20,
  803,
  1033917596,
  1,
  815
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  119,
  1,
  1,
  0,
  4402,
  'short_description',
  0,
  810,
  21,
  815,
  1033917596,
  1,
  816
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  119,
  1,
  1,
  0,
  4403,
  'short_description',
  0,
  817,
  22,
  816,
  1033917596,
  1,
  810
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  119,
  1,
  1,
  0,
  4404,
  'short_description',
  0,
  818,
  23,
  810,
  1033917596,
  1,
  817
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  119,
  1,
  1,
  0,
  4405,
  'short_description',
  0,
  802,
  24,
  817,
  1033917596,
  1,
  818
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  119,
  1,
  1,
  0,
  4406,
  'short_description',
  0,
  803,
  25,
  818,
  1033917596,
  1,
  802
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  119,
  1,
  1,
  0,
  4407,
  'short_description',
  0,
  819,
  26,
  802,
  1033917596,
  1,
  803
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  119,
  1,
  1,
  0,
  4408,
  'short_description',
  0,
  820,
  27,
  803,
  1033917596,
  1,
  819
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  119,
  1,
  1,
  0,
  4409,
  'short_description',
  0,
  814,
  28,
  819,
  1033917596,
  1,
  820
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  119,
  1,
  1,
  0,
  4410,
  'short_description',
  0,
  821,
  29,
  820,
  1033917596,
  1,
  814
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  119,
  1,
  1,
  0,
  4411,
  'short_description',
  0,
  822,
  30,
  814,
  1033917596,
  1,
  821
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  119,
  1,
  1,
  0,
  4412,
  'short_description',
  0,
  823,
  31,
  821,
  1033917596,
  1,
  822
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  119,
  1,
  1,
  0,
  4413,
  'short_description',
  0,
  814,
  32,
  822,
  1033917596,
  1,
  823
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  119,
  1,
  1,
  0,
  4414,
  'short_description',
  0,
  802,
  33,
  823,
  1033917596,
  1,
  814
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  119,
  1,
  1,
  0,
  4415,
  'short_description',
  0,
  824,
  34,
  814,
  1033917596,
  1,
  802
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  119,
  1,
  1,
  0,
  4416,
  'short_description',
  0,
  807,
  35,
  802,
  1033917596,
  1,
  824
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  119,
  1,
  1,
  0,
  4417,
  'short_description',
  0,
  808,
  36,
  824,
  1033917596,
  1,
  807
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  119,
  1,
  1,
  0,
  4418,
  'short_description',
  0,
  825,
  37,
  807,
  1033917596,
  1,
  808
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  119,
  1,
  1,
  0,
  4419,
  'short_description',
  0,
  814,
  38,
  808,
  1033917596,
  1,
  825
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  119,
  1,
  1,
  0,
  4420,
  'short_description',
  0,
  826,
  39,
  825,
  1033917596,
  1,
  814
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  119,
  1,
  1,
  0,
  4421,
  'short_description',
  0,
  802,
  40,
  814,
  1033917596,
  1,
  826
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4422,
  'description',
  0,
  803,
  41,
  826,
  1033917596,
  1,
  802
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4423,
  'description',
  0,
  805,
  42,
  802,
  1033917596,
  1,
  803
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4424,
  'description',
  0,
  810,
  43,
  803,
  1033917596,
  1,
  805
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4425,
  'description',
  0,
  827,
  44,
  805,
  1033917596,
  1,
  810
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4426,
  'description',
  0,
  828,
  45,
  810,
  1033917596,
  1,
  827
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4427,
  'description',
  0,
  829,
  46,
  827,
  1033917596,
  1,
  828
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4428,
  'description',
  0,
  830,
  47,
  828,
  1033917596,
  1,
  829
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4429,
  'description',
  0,
  831,
  48,
  829,
  1033917596,
  1,
  830
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4430,
  'description',
  0,
  832,
  49,
  830,
  1033917596,
  1,
  831
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4431,
  'description',
  0,
  833,
  50,
  831,
  1033917596,
  1,
  832
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4432,
  'description',
  0,
  834,
  51,
  832,
  1033917596,
  1,
  833
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4433,
  'description',
  0,
  835,
  52,
  833,
  1033917596,
  1,
  834
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4434,
  'description',
  0,
  836,
  53,
  834,
  1033917596,
  1,
  835
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4435,
  'description',
  0,
  837,
  54,
  835,
  1033917596,
  1,
  836
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4436,
  'description',
  0,
  814,
  55,
  836,
  1033917596,
  1,
  837
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4437,
  'description',
  0,
  834,
  56,
  837,
  1033917596,
  1,
  814
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4438,
  'description',
  0,
  813,
  57,
  814,
  1033917596,
  1,
  834
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4439,
  'description',
  0,
  838,
  58,
  834,
  1033917596,
  1,
  813
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4440,
  'description',
  0,
  839,
  59,
  813,
  1033917596,
  1,
  838
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4441,
  'description',
  0,
  833,
  60,
  838,
  1033917596,
  1,
  839
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4442,
  'description',
  0,
  840,
  61,
  839,
  1033917596,
  1,
  833
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4443,
  'description',
  0,
  841,
  62,
  833,
  1033917596,
  1,
  840
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4444,
  'description',
  0,
  842,
  63,
  840,
  1033917596,
  1,
  841
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4445,
  'description',
  0,
  836,
  64,
  841,
  1033917596,
  1,
  842
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4446,
  'description',
  0,
  843,
  65,
  842,
  1033917596,
  1,
  836
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4447,
  'description',
  0,
  844,
  66,
  836,
  1033917596,
  1,
  843
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4448,
  'description',
  0,
  845,
  67,
  843,
  1033917596,
  1,
  844
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4449,
  'description',
  0,
  801,
  68,
  844,
  1033917596,
  1,
  845
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4450,
  'description',
  0,
  846,
  69,
  845,
  1033917596,
  1,
  801
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4451,
  'description',
  0,
  847,
  70,
  801,
  1033917596,
  1,
  846
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4452,
  'description',
  0,
  848,
  71,
  846,
  1033917596,
  1,
  847
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4453,
  'description',
  0,
  810,
  72,
  847,
  1033917596,
  1,
  848
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4454,
  'description',
  0,
  849,
  73,
  848,
  1033917596,
  1,
  810
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4455,
  'description',
  0,
  850,
  74,
  810,
  1033917596,
  1,
  849
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4456,
  'description',
  0,
  801,
  75,
  849,
  1033917596,
  1,
  850
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4457,
  'description',
  0,
  810,
  76,
  850,
  1033917596,
  1,
  801
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4458,
  'description',
  0,
  851,
  77,
  801,
  1033917596,
  1,
  810
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4459,
  'description',
  0,
  852,
  78,
  810,
  1033917596,
  1,
  851
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4460,
  'description',
  0,
  821,
  79,
  851,
  1033917596,
  1,
  852
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4461,
  'description',
  0,
  809,
  80,
  852,
  1033917596,
  1,
  821
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4462,
  'description',
  0,
  853,
  81,
  821,
  1033917596,
  1,
  809
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4463,
  'description',
  0,
  854,
  82,
  809,
  1033917596,
  1,
  853
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4464,
  'description',
  0,
  855,
  83,
  853,
  1033917596,
  1,
  854
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4465,
  'description',
  0,
  856,
  84,
  854,
  1033917596,
  1,
  855
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4466,
  'description',
  0,
  857,
  85,
  855,
  1033917596,
  1,
  856
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4467,
  'description',
  0,
  858,
  86,
  856,
  1033917596,
  1,
  857
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4468,
  'description',
  0,
  859,
  87,
  857,
  1033917596,
  1,
  858
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4469,
  'description',
  0,
  860,
  88,
  858,
  1033917596,
  1,
  859
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4470,
  'description',
  0,
  833,
  89,
  859,
  1033917596,
  1,
  860
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4471,
  'description',
  0,
  861,
  90,
  860,
  1033917596,
  1,
  833
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4472,
  'description',
  0,
  862,
  91,
  833,
  1033917596,
  1,
  861
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4473,
  'description',
  0,
  815,
  92,
  861,
  1033917596,
  1,
  862
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4474,
  'description',
  0,
  863,
  93,
  862,
  1033917596,
  1,
  815
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4475,
  'description',
  0,
  864,
  94,
  815,
  1033917596,
  1,
  863
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4476,
  'description',
  0,
  865,
  95,
  863,
  1033917596,
  1,
  864
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4477,
  'description',
  0,
  813,
  96,
  864,
  1033917596,
  1,
  865
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4478,
  'description',
  0,
  866,
  97,
  865,
  1033917596,
  1,
  813
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4479,
  'description',
  0,
  828,
  98,
  813,
  1033917596,
  1,
  866
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4480,
  'description',
  0,
  867,
  99,
  866,
  1033917596,
  1,
  828
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4481,
  'description',
  0,
  802,
  100,
  828,
  1033917596,
  1,
  867
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4482,
  'description',
  0,
  803,
  101,
  867,
  1033917596,
  1,
  802
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4483,
  'description',
  0,
  843,
  102,
  802,
  1033917596,
  1,
  803
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4484,
  'description',
  0,
  868,
  103,
  803,
  1033917596,
  1,
  843
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4485,
  'description',
  0,
  844,
  104,
  843,
  1033917596,
  1,
  868
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4486,
  'description',
  0,
  869,
  105,
  868,
  1033917596,
  1,
  844
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4487,
  'description',
  0,
  870,
  106,
  844,
  1033917596,
  1,
  869
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4488,
  'description',
  0,
  871,
  107,
  869,
  1033917596,
  1,
  870
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4489,
  'description',
  0,
  833,
  108,
  870,
  1033917596,
  1,
  871
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4490,
  'description',
  0,
  872,
  109,
  871,
  1033917596,
  1,
  833
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4491,
  'description',
  0,
  809,
  110,
  833,
  1033917596,
  1,
  872
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4492,
  'description',
  0,
  873,
  111,
  872,
  1033917596,
  1,
  809
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4493,
  'description',
  0,
  836,
  112,
  809,
  1033917596,
  1,
  873
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4494,
  'description',
  0,
  842,
  113,
  873,
  1033917596,
  1,
  836
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4495,
  'description',
  0,
  874,
  114,
  836,
  1033917596,
  1,
  842
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4496,
  'description',
  0,
  833,
  115,
  842,
  1033917596,
  1,
  874
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4497,
  'description',
  0,
  875,
  116,
  874,
  1033917596,
  1,
  833
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4498,
  'description',
  0,
  814,
  117,
  833,
  1033917596,
  1,
  875
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4499,
  'description',
  0,
  802,
  118,
  875,
  1033917596,
  1,
  814
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4500,
  'description',
  0,
  803,
  119,
  814,
  1033917596,
  1,
  802
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4501,
  'description',
  0,
  874,
  120,
  802,
  1033917596,
  1,
  803
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4502,
  'description',
  0,
  876,
  121,
  803,
  1033917596,
  1,
  874
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4503,
  'description',
  0,
  877,
  122,
  874,
  1033917596,
  1,
  876
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4504,
  'description',
  0,
  878,
  123,
  876,
  1033917596,
  1,
  877
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4505,
  'description',
  0,
  879,
  124,
  877,
  1033917596,
  1,
  878
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4506,
  'description',
  0,
  801,
  125,
  878,
  1033917596,
  1,
  879
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4507,
  'description',
  0,
  814,
  126,
  879,
  1033917596,
  1,
  801
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4508,
  'description',
  0,
  812,
  127,
  801,
  1033917596,
  1,
  814
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4509,
  'description',
  0,
  833,
  128,
  814,
  1033917596,
  1,
  812
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4510,
  'description',
  0,
  880,
  129,
  812,
  1033917596,
  1,
  833
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4511,
  'description',
  0,
  881,
  130,
  833,
  1033917596,
  1,
  880
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4512,
  'description',
  0,
  813,
  131,
  880,
  1033917596,
  1,
  881
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4513,
  'description',
  0,
  814,
  132,
  881,
  1033917596,
  1,
  813
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4514,
  'description',
  0,
  802,
  133,
  813,
  1033917596,
  1,
  814
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4515,
  'description',
  0,
  803,
  134,
  814,
  1033917596,
  1,
  802
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4516,
  'description',
  0,
  830,
  135,
  802,
  1033917596,
  1,
  803
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4517,
  'description',
  0,
  831,
  136,
  803,
  1033917596,
  1,
  830
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4518,
  'description',
  0,
  832,
  137,
  830,
  1033917596,
  1,
  831
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4519,
  'description',
  0,
  835,
  138,
  831,
  1033917596,
  1,
  832
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4520,
  'description',
  0,
  863,
  139,
  832,
  1033917596,
  1,
  835
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4521,
  'description',
  0,
  864,
  140,
  835,
  1033917596,
  1,
  863
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4522,
  'description',
  0,
  836,
  141,
  863,
  1033917596,
  1,
  864
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4523,
  'description',
  0,
  882,
  142,
  864,
  1033917596,
  1,
  836
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4524,
  'description',
  0,
  876,
  143,
  836,
  1033917596,
  1,
  882
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4525,
  'description',
  0,
  883,
  144,
  882,
  1033917596,
  1,
  876
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4526,
  'description',
  0,
  862,
  145,
  876,
  1033917596,
  1,
  883
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4527,
  'description',
  0,
  878,
  146,
  883,
  1033917596,
  1,
  862
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4528,
  'description',
  0,
  884,
  147,
  862,
  1033917596,
  1,
  878
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4529,
  'description',
  0,
  885,
  148,
  878,
  1033917596,
  1,
  884
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4530,
  'description',
  0,
  886,
  149,
  884,
  1033917596,
  1,
  885
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4531,
  'description',
  0,
  887,
  150,
  885,
  1033917596,
  1,
  886
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4532,
  'description',
  0,
  809,
  151,
  886,
  1033917596,
  1,
  887
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4533,
  'description',
  0,
  802,
  152,
  887,
  1033917596,
  1,
  809
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4534,
  'description',
  0,
  803,
  153,
  809,
  1033917596,
  1,
  802
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4535,
  'description',
  0,
  888,
  154,
  802,
  1033917596,
  1,
  803
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4536,
  'description',
  0,
  825,
  155,
  803,
  1033917596,
  1,
  888
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4537,
  'description',
  0,
  889,
  156,
  888,
  1033917596,
  1,
  825
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4538,
  'description',
  0,
  890,
  157,
  825,
  1033917596,
  1,
  889
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4539,
  'description',
  0,
  814,
  158,
  889,
  1033917596,
  1,
  890
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4540,
  'description',
  0,
  802,
  159,
  890,
  1033917596,
  1,
  814
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4541,
  'description',
  0,
  803,
  160,
  814,
  1033917596,
  1,
  802
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4542,
  'description',
  0,
  891,
  161,
  802,
  1033917596,
  1,
  803
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4543,
  'description',
  0,
  892,
  162,
  803,
  1033917596,
  1,
  891
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4544,
  'description',
  0,
  893,
  163,
  891,
  1033917596,
  1,
  892
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4545,
  'description',
  0,
  894,
  164,
  892,
  1033917596,
  1,
  893
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4546,
  'description',
  0,
  895,
  165,
  893,
  1033917596,
  1,
  894
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4547,
  'description',
  0,
  896,
  166,
  894,
  1033917596,
  1,
  895
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4548,
  'description',
  0,
  801,
  167,
  895,
  1033917596,
  1,
  896
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4549,
  'description',
  0,
  897,
  168,
  896,
  1033917596,
  1,
  801
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4550,
  'description',
  0,
  898,
  169,
  801,
  1033917596,
  1,
  897
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4551,
  'description',
  0,
  899,
  170,
  897,
  1033917596,
  1,
  898
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4552,
  'description',
  0,
  900,
  171,
  898,
  1033917596,
  1,
  899
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4553,
  'description',
  0,
  801,
  172,
  899,
  1033917596,
  1,
  900
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4554,
  'description',
  0,
  810,
  173,
  900,
  1033917596,
  1,
  801
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4555,
  'description',
  0,
  901,
  174,
  801,
  1033917596,
  1,
  810
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4556,
  'description',
  0,
  902,
  175,
  810,
  1033917596,
  1,
  901
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4557,
  'description',
  0,
  903,
  176,
  901,
  1033917596,
  1,
  902
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4558,
  'description',
  0,
  904,
  177,
  902,
  1033917596,
  1,
  903
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4559,
  'description',
  0,
  814,
  178,
  903,
  1033917596,
  1,
  904
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4560,
  'description',
  0,
  874,
  179,
  904,
  1033917596,
  1,
  814
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4561,
  'description',
  0,
  905,
  180,
  814,
  1033917596,
  1,
  874
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4562,
  'description',
  0,
  894,
  181,
  874,
  1033917596,
  1,
  905
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4563,
  'description',
  0,
  888,
  182,
  905,
  1033917596,
  1,
  894
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4564,
  'description',
  0,
  906,
  183,
  894,
  1033917596,
  1,
  888
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4565,
  'description',
  0,
  881,
  184,
  888,
  1033917596,
  1,
  906
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4566,
  'description',
  0,
  813,
  185,
  906,
  1033917596,
  1,
  881
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4567,
  'description',
  0,
  814,
  186,
  881,
  1033917596,
  1,
  813
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4568,
  'description',
  0,
  907,
  187,
  813,
  1033917596,
  1,
  814
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4569,
  'description',
  0,
  802,
  188,
  814,
  1033917596,
  1,
  907
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4570,
  'description',
  0,
  803,
  189,
  907,
  1033917596,
  1,
  802
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4571,
  'description',
  0,
  908,
  190,
  802,
  1033917596,
  1,
  803
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4572,
  'description',
  0,
  884,
  191,
  803,
  1033917596,
  1,
  908
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4573,
  'description',
  0,
  885,
  192,
  908,
  1033917596,
  1,
  884
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4574,
  'description',
  0,
  909,
  193,
  884,
  1033917596,
  1,
  885
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4575,
  'description',
  0,
  838,
  194,
  885,
  1033917596,
  1,
  909
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4576,
  'description',
  0,
  910,
  195,
  909,
  1033917596,
  1,
  838
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4577,
  'description',
  0,
  888,
  196,
  838,
  1033917596,
  1,
  910
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4578,
  'description',
  0,
  911,
  197,
  910,
  1033917596,
  1,
  888
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4579,
  'description',
  0,
  912,
  198,
  888,
  1033917596,
  1,
  911
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4580,
  'description',
  0,
  823,
  199,
  911,
  1033917596,
  1,
  912
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4581,
  'description',
  0,
  913,
  200,
  912,
  1033917596,
  1,
  823
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4582,
  'description',
  0,
  914,
  201,
  823,
  1033917596,
  1,
  913
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4583,
  'description',
  0,
  836,
  202,
  913,
  1033917596,
  1,
  914
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4584,
  'description',
  0,
  805,
  203,
  914,
  1033917596,
  1,
  836
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4585,
  'description',
  0,
  882,
  204,
  836,
  1033917596,
  1,
  805
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4586,
  'description',
  0,
  915,
  205,
  805,
  1033917596,
  1,
  882
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4587,
  'description',
  0,
  801,
  206,
  882,
  1033917596,
  1,
  915
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4588,
  'description',
  0,
  916,
  207,
  915,
  1033917596,
  1,
  801
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4589,
  'description',
  0,
  917,
  208,
  801,
  1033917596,
  1,
  916
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4590,
  'description',
  0,
  816,
  209,
  916,
  1033917596,
  1,
  917
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4591,
  'description',
  0,
  918,
  210,
  917,
  1033917596,
  1,
  816
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4592,
  'description',
  0,
  919,
  211,
  816,
  1033917596,
  1,
  918
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4593,
  'description',
  0,
  920,
  212,
  918,
  1033917596,
  1,
  919
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4594,
  'description',
  0,
  816,
  213,
  919,
  1033917596,
  1,
  920
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4595,
  'description',
  0,
  921,
  214,
  920,
  1033917596,
  1,
  816
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4596,
  'description',
  0,
  922,
  215,
  816,
  1033917596,
  1,
  921
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4597,
  'description',
  0,
  923,
  216,
  921,
  1033917596,
  1,
  922
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4598,
  'description',
  0,
  802,
  217,
  922,
  1033917596,
  1,
  923
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4599,
  'description',
  0,
  803,
  218,
  923,
  1033917596,
  1,
  802
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4600,
  'description',
  0,
  833,
  219,
  802,
  1033917596,
  1,
  803
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4601,
  'description',
  0,
  861,
  220,
  803,
  1033917596,
  1,
  833
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4602,
  'description',
  0,
  924,
  221,
  833,
  1033917596,
  1,
  861
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4603,
  'description',
  0,
  914,
  222,
  861,
  1033917596,
  1,
  924
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4604,
  'description',
  0,
  848,
  223,
  924,
  1033917596,
  1,
  914
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4605,
  'description',
  0,
  802,
  224,
  914,
  1033917596,
  1,
  848
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4606,
  'description',
  0,
  925,
  225,
  848,
  1033917596,
  1,
  802
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4607,
  'description',
  0,
  819,
  226,
  802,
  1033917596,
  1,
  925
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4608,
  'description',
  0,
  926,
  227,
  925,
  1033917596,
  1,
  819
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4609,
  'description',
  0,
  927,
  228,
  819,
  1033917596,
  1,
  926
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4610,
  'description',
  0,
  928,
  229,
  926,
  1033917596,
  1,
  927
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4611,
  'description',
  0,
  929,
  230,
  927,
  1033917596,
  1,
  928
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4612,
  'description',
  0,
  930,
  231,
  928,
  1033917596,
  1,
  929
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4613,
  'description',
  0,
  802,
  232,
  929,
  1033917596,
  1,
  930
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4614,
  'description',
  0,
  803,
  233,
  930,
  1033917596,
  1,
  802
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4615,
  'description',
  0,
  931,
  234,
  802,
  1033917596,
  1,
  803
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4616,
  'description',
  0,
  822,
  235,
  803,
  1033917596,
  1,
  931
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4617,
  'description',
  0,
  802,
  236,
  931,
  1033917596,
  1,
  822
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4618,
  'description',
  0,
  803,
  237,
  822,
  1033917596,
  1,
  802
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4619,
  'description',
  0,
  857,
  238,
  802,
  1033917596,
  1,
  803
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4620,
  'description',
  0,
  932,
  239,
  803,
  1033917596,
  1,
  857
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4621,
  'description',
  0,
  933,
  240,
  857,
  1033917596,
  1,
  932
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4622,
  'description',
  0,
  802,
  241,
  932,
  1033917596,
  1,
  933
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4623,
  'description',
  0,
  824,
  242,
  933,
  1033917596,
  1,
  802
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4624,
  'description',
  0,
  933,
  243,
  802,
  1033917596,
  1,
  824
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4625,
  'description',
  0,
  934,
  244,
  824,
  1033917596,
  1,
  933
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4626,
  'description',
  0,
  935,
  245,
  933,
  1033917596,
  1,
  934
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4627,
  'description',
  0,
  930,
  246,
  934,
  1033917596,
  1,
  935
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4628,
  'description',
  0,
  936,
  247,
  935,
  1033917596,
  1,
  930
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4629,
  'description',
  0,
  801,
  248,
  930,
  1033917596,
  1,
  936
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4630,
  'description',
  0,
  937,
  249,
  936,
  1033917596,
  1,
  801
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4631,
  'description',
  0,
  802,
  250,
  801,
  1033917596,
  1,
  937
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4632,
  'description',
  0,
  803,
  251,
  937,
  1033917596,
  1,
  802
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4633,
  'description',
  0,
  938,
  252,
  802,
  1033917596,
  1,
  803
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4634,
  'description',
  0,
  936,
  253,
  803,
  1033917596,
  1,
  938
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4635,
  'description',
  0,
  801,
  254,
  938,
  1033917596,
  1,
  936
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4636,
  'description',
  0,
  939,
  255,
  936,
  1033917596,
  1,
  801
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4637,
  'description',
  0,
  940,
  256,
  801,
  1033917596,
  1,
  939
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4638,
  'description',
  0,
  941,
  257,
  939,
  1033917596,
  1,
  940
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4639,
  'description',
  0,
  936,
  258,
  940,
  1033917596,
  1,
  941
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4640,
  'description',
  0,
  801,
  259,
  941,
  1033917596,
  1,
  936
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4641,
  'description',
  0,
  881,
  260,
  936,
  1033917596,
  1,
  801
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4642,
  'description',
  0,
  942,
  261,
  801,
  1033917596,
  1,
  881
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4643,
  'description',
  0,
  943,
  262,
  881,
  1033917596,
  1,
  942
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4644,
  'description',
  0,
  822,
  263,
  942,
  1033917596,
  1,
  943
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4645,
  'description',
  0,
  944,
  264,
  943,
  1033917596,
  1,
  822
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4646,
  'description',
  0,
  945,
  265,
  822,
  1033917596,
  1,
  944
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4647,
  'description',
  0,
  946,
  266,
  944,
  1033917596,
  1,
  945
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4648,
  'description',
  0,
  801,
  267,
  945,
  1033917596,
  1,
  946
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4649,
  'description',
  0,
  947,
  268,
  946,
  1033917596,
  1,
  801
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4650,
  'description',
  0,
  897,
  269,
  801,
  1033917596,
  1,
  947
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4651,
  'description',
  0,
  921,
  270,
  947,
  1033917596,
  1,
  897
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4652,
  'description',
  0,
  948,
  271,
  897,
  1033917596,
  1,
  921
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4653,
  'description',
  0,
  949,
  272,
  921,
  1033917596,
  1,
  948
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4654,
  'description',
  0,
  928,
  273,
  948,
  1033917596,
  1,
  949
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4655,
  'description',
  0,
  948,
  274,
  949,
  1033917596,
  1,
  928
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4656,
  'description',
  0,
  949,
  275,
  928,
  1033917596,
  1,
  948
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4657,
  'description',
  0,
  833,
  276,
  948,
  1033917596,
  1,
  949
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4658,
  'description',
  0,
  948,
  277,
  949,
  1033917596,
  1,
  833
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4659,
  'description',
  0,
  949,
  278,
  833,
  1033917596,
  1,
  948
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4660,
  'description',
  0,
  950,
  279,
  948,
  1033917596,
  1,
  949
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4661,
  'description',
  0,
  857,
  280,
  949,
  1033917596,
  1,
  950
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  156,
  1,
  1,
  0,
  4662,
  'description',
  0,
  0,
  281,
  950,
  1033917596,
  1,
  857
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  6,
  3,
  4,
  0,
  4663,
  'name',
  0,
  951,
  0,
  0,
  1033917596,
  2,
  930
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  7,
  3,
  4,
  0,
  4664,
  'description',
  0,
  952,
  1,
  930,
  1033917596,
  2,
  951
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  7,
  3,
  4,
  0,
  4665,
  'description',
  0,
  0,
  2,
  951,
  1033917596,
  2,
  952
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  8,
  4,
  10,
  0,
  4666,
  'first_name',
  0,
  954,
  0,
  0,
  1033920665,
  2,
  953
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  9,
  4,
  10,
  0,
  4667,
  'last_name',
  0,
  953,
  1,
  953,
  1033920665,
  2,
  954
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  12,
  4,
  10,
  0,
  4668,
  'user_account',
  0,
  955,
  2,
  954,
  1033920665,
  2,
  953
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  12,
  4,
  10,
  0,
  4669,
  'user_account',
  0,
  927,
  3,
  953,
  1033920665,
  2,
  955
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  12,
  4,
  10,
  0,
  4670,
  'user_account',
  0,
  0,
  4,
  955,
  1033920665,
  2,
  927
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  6,
  3,
  11,
  0,
  4671,
  'name',
  0,
  957,
  0,
  0,
  1033920746,
  2,
  956
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  6,
  3,
  11,
  0,
  4672,
  'name',
  0,
  0,
  1,
  956,
  1033920746,
  2,
  957
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  6,
  3,
  12,
  0,
  4673,
  'name',
  0,
  930,
  0,
  0,
  1033920775,
  2,
  958
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  6,
  3,
  12,
  0,
  4674,
  'name',
  0,
  0,
  1,
  958,
  1033920775,
  2,
  930
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  6,
  3,
  13,
  0,
  4675,
  'name',
  0,
  0,
  0,
  0,
  1033920794,
  2,
  959
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  8,
  4,
  14,
  0,
  4676,
  'first_name',
  0,
  954,
  0,
  0,
  1033920830,
  2,
  958
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  9,
  4,
  14,
  0,
  4677,
  'last_name',
  0,
  960,
  1,
  958,
  1033920830,
  2,
  954
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  12,
  4,
  14,
  0,
  4678,
  'user_account',
  0,
  955,
  2,
  954,
  1033920830,
  2,
  960
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  12,
  4,
  14,
  0,
  4679,
  'user_account',
  0,
  927,
  3,
  960,
  1033920830,
  2,
  955
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  12,
  4,
  14,
  0,
  4680,
  'user_account',
  0,
  0,
  4,
  955,
  1033920830,
  2,
  927
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  4,
  1,
  41,
  0,
  4681,
  'name',
  0,
  0,
  0,
  0,
  1060695457,
  3,
  961
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  6,
  3,
  42,
  0,
  4682,
  'name',
  0,
  930,
  0,
  0,
  1072180330,
  2,
  953
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  6,
  3,
  42,
  0,
  4683,
  'name',
  0,
  954,
  1,
  953,
  1072180330,
  2,
  930
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  7,
  3,
  42,
  0,
  4684,
  'description',
  0,
  952,
  2,
  930,
  1072180330,
  2,
  954
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  7,
  3,
  42,
  0,
  4685,
  'description',
  0,
  816,
  3,
  954,
  1072180330,
  2,
  952
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  7,
  3,
  42,
  0,
  4686,
  'description',
  0,
  814,
  4,
  952,
  1072180330,
  2,
  816
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  7,
  3,
  42,
  0,
  4687,
  'description',
  0,
  953,
  5,
  816,
  1072180330,
  2,
  814
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  7,
  3,
  42,
  0,
  4688,
  'description',
  0,
  954,
  6,
  814,
  1072180330,
  2,
  953
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  7,
  3,
  42,
  0,
  4689,
  'description',
  0,
  0,
  7,
  953,
  1072180330,
  2,
  954
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  4,
  1,
  45,
  0,
  4690,
  'name',
  0,
  0,
  0,
  0,
  1079684190,
  4,
  812
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  4,
  1,
  49,
  0,
  4691,
  'name',
  0,
  0,
  0,
  0,
  1080220197,
  3,
  962
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  4,
  1,
  50,
  0,
  4692,
  'name',
  0,
  0,
  0,
  0,
  1080220220,
  3,
  963
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  4,
  1,
  51,
  0,
  4693,
  'name',
  0,
  0,
  0,
  0,
  1080220233,
  3,
  964
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  159,
  14,
  52,
  0,
  4694,
  'name',
  0,
  965,
  0,
  0,
  1082016591,
  4,
  877
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  159,
  14,
  52,
  0,
  4695,
  'name',
  0,
  966,
  1,
  877,
  1082016591,
  4,
  965
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  159,
  14,
  52,
  0,
  4696,
  'name',
  0,
  0,
  2,
  965,
  1082016591,
  4,
  966
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  176,
  15,
  54,
  0,
  4697,
  'id',
  0,
  0,
  0,
  0,
  1082016652,
  5,
  967
);
INSERT INTO ezsearch_object_word_link (
  contentclass_attribute_id,
  contentclass_id,
  contentobject_id,
  frequency,
  id,
  identifier,
  integer_value,
  next_word_id,
  placement,
  prev_word_id,
  published,
  section_id,
  word_id
) VALUES (
  4,
  1,
  56,
  0,
  4698,
  'name',
  0,
  0,
  0,
  0,
  1103023132,
  5,
  968
);

INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  800,
  1,
  'welcome'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  801,
  1,
  'to'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  802,
  1,
  'ez'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  803,
  1,
  'publish'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  804,
  1,
  'this'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  805,
  1,
  'is'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  806,
  1,
  'plain'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  807,
  1,
  'site'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  808,
  1,
  'package'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  809,
  1,
  'with'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  810,
  1,
  'a'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  811,
  1,
  'limited'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  812,
  2,
  'setup'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  813,
  1,
  'of'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  814,
  2,
  'the'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  815,
  1,
  'functionality'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  816,
  2,
  'for'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  817,
  1,
  'full'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  818,
  1,
  'blown'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  819,
  1,
  'please'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  820,
  1,
  'chose'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  821,
  1,
  'website'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  822,
  1,
  'interface'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  823,
  1,
  'or'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  824,
  1,
  'flow'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  825,
  1,
  'at'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  826,
  1,
  'installation'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  827,
  1,
  'popular'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  828,
  1,
  'open'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  829,
  1,
  'source'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  830,
  1,
  'content'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  831,
  1,
  'management'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  832,
  1,
  'system'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  833,
  1,
  'and'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  834,
  1,
  'development'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  835,
  1,
  'framework'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  836,
  1,
  'it'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  837,
  1,
  'allows'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  838,
  1,
  'professional'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  839,
  1,
  'customized'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  840,
  1,
  'dynamic'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  841,
  1,
  'web'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  842,
  1,
  'solutions'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  843,
  1,
  'can'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  844,
  1,
  'be'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  845,
  1,
  'used'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  846,
  1,
  'build'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  847,
  1,
  'anything'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  848,
  1,
  'from'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  849,
  1,
  'personal'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  850,
  1,
  'homepage'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  851,
  1,
  'multinational'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  852,
  1,
  'corporate'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  853,
  1,
  'role'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  854,
  1,
  'based'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  855,
  1,
  'multiuser'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  856,
  1,
  'access'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  857,
  1,
  'online'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  858,
  1,
  'shopping'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  859,
  1,
  'discussion'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  860,
  1,
  'forums'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  861,
  1,
  'other'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  862,
  1,
  'advanced'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  863,
  1,
  'in'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  864,
  1,
  'addition'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  865,
  1,
  'because'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  866,
  1,
  'its'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  867,
  1,
  'nature'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  868,
  1,
  'easily'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  869,
  1,
  'plugged'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  870,
  1,
  'into'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  871,
  1,
  'communicate'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  872,
  1,
  'coexist'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  873,
  1,
  'existing'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  874,
  1,
  'documentation'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  875,
  1,
  'guidance'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  876,
  1,
  'covers'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  877,
  2,
  'common'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  878,
  1,
  'topics'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  879,
  1,
  'related'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  880,
  1,
  'daily'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  881,
  1,
  'use'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  882,
  1,
  'also'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  883,
  1,
  'some'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  884,
  1,
  'people'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  885,
  1,
  'who'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  886,
  1,
  'are'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  887,
  1,
  'unfamiliar'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  888,
  1,
  'should'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  889,
  1,
  'least'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  890,
  1,
  'read'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  891,
  1,
  'basics'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  892,
  1,
  'chapter'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  893,
  1,
  'if'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  894,
  1,
  'you'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  895,
  1,
  're'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  896,
  1,
  'unable'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  897,
  1,
  'find'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  898,
  1,
  'an'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  899,
  1,
  'answer'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  900,
  1,
  'solution'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  901,
  1,
  'specific'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  902,
  1,
  'question'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  903,
  1,
  'problem'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  904,
  1,
  'within'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  905,
  1,
  'pages'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  906,
  1,
  'make'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  907,
  1,
  'official'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  908,
  1,
  'forum'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  909,
  1,
  'need'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  910,
  1,
  'help'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  911,
  1,
  'purchase'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  912,
  1,
  'support'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  913,
  1,
  'consulting'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  914,
  1,
  'services'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  915,
  1,
  'possible'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  916,
  1,
  'sign'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  917,
  1,
  'up'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  918,
  1,
  'various'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  919,
  1,
  'training'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  920,
  1,
  'sessions'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  921,
  1,
  'more'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  922,
  1,
  'information'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  923,
  1,
  'about'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  924,
  1,
  'products'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  925,
  1,
  'systems'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  926,
  1,
  'visit'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  927,
  3,
  'ez.no'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  928,
  1,
  'tutorials'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  929,
  1,
  'new'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  930,
  4,
  'users'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  931,
  1,
  'administration'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  932,
  1,
  'editor'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  933,
  1,
  'video'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  934,
  1,
  'tutorial'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  935,
  1,
  'experienced'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  936,
  1,
  'how'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  937,
  1,
  'develop'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  938,
  1,
  'extensions'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  939,
  1,
  'create'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  940,
  1,
  'custom'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  941,
  1,
  'workflow'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  942,
  1,
  'rest'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  943,
  1,
  'api'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  944,
  1,
  'asynchronous'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  945,
  1,
  'publishing'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  946,
  1,
  'upgrading'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  947,
  1,
  '4.5'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  948,
  1,
  'amp'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  949,
  1,
  'nbsp'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  950,
  1,
  'videos'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  951,
  1,
  'main'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  952,
  2,
  'group'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  953,
  2,
  'anonymous'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  954,
  3,
  'user'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  955,
  2,
  'nospam'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  956,
  1,
  'guest'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  957,
  1,
  'accounts'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  958,
  2,
  'administrator'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  959,
  1,
  'editors'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  960,
  1,
  'admin'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  961,
  1,
  'media'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  962,
  1,
  'images'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  963,
  1,
  'files'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  964,
  1,
  'multimedia'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  965,
  1,
  'ini'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  966,
  1,
  'settings'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  967,
  1,
  'sitestyle_identifier'
);
INSERT INTO ezsearch_word (
  id,
  object_count,
  word
) VALUES (
  968,
  1,
  'design'
);

INSERT INTO ezsection (
  id,
  identifier,
  locale,
  name,
  navigation_part_identifier
) VALUES (
  1,
  'standard',
  '',
  'Standard',
  'ezcontentnavigationpart'
);
INSERT INTO ezsection (
  id,
  identifier,
  locale,
  name,
  navigation_part_identifier
) VALUES (
  2,
  'users',
  '',
  'Users',
  'ezusernavigationpart'
);
INSERT INTO ezsection (
  id,
  identifier,
  locale,
  name,
  navigation_part_identifier
) VALUES (
  3,
  'media',
  '',
  'Media',
  'ezmedianavigationpart'
);
INSERT INTO ezsection (
  id,
  identifier,
  locale,
  name,
  navigation_part_identifier
) VALUES (
  4,
  'setup',
  '',
  'Setup',
  'ezsetupnavigationpart'
);
INSERT INTO ezsection (
  id,
  identifier,
  locale,
  name,
  navigation_part_identifier
) VALUES (
  5,
  'design',
  '',
  'Design',
  'ezvisualnavigationpart'
);

INSERT INTO ezsite_data (
  name,
  value
) VALUES (
  'ezpublish-release',
  '1'
);
INSERT INTO ezsite_data (
  name,
  value
) VALUES (
  'ezpublish-version',
  '5.0.0alpha1'
);

INSERT INTO ezurl (
  created,
  id,
  is_valid,
  last_checked,
  modified,
  original_url_md5,
  url
) VALUES (
  1082368571,
  4,
  1,
  0,
  1082368571,
  '41caff1d7f5ad51e70ad46abbcf28fb7',
  'http://ez.no/community/forum'
);
INSERT INTO ezurl (
  created,
  id,
  is_valid,
  last_checked,
  modified,
  original_url_md5,
  url
) VALUES (
  1082368571,
  8,
  1,
  0,
  1082368571,
  'dfcdb471b240d964dc3f57b998eb0533',
  'http://ez.no'
);
INSERT INTO ezurl (
  created,
  id,
  is_valid,
  last_checked,
  modified,
  original_url_md5,
  url
) VALUES (
  1301057834,
  9,
  1,
  0,
  1301057834,
  'bb9c47d334fd775f1c54c45d460e6b2a',
  'http://doc.ez.no/'
);
INSERT INTO ezurl (
  created,
  id,
  is_valid,
  last_checked,
  modified,
  original_url_md5,
  url
) VALUES (
  1301057834,
  10,
  1,
  0,
  1301057834,
  'ae76fd1d17de21067cf13101f11689b1',
  'http://ez.no/eZPublish/eZ-Publish-Enterprise-Subscription/Support-Services'
);
INSERT INTO ezurl (
  created,
  id,
  is_valid,
  last_checked,
  modified,
  original_url_md5,
  url
) VALUES (
  1301057834,
  11,
  1,
  0,
  1301057834,
  '0c098a23ef9c7cae63ee8f85cf504b2d',
  'http://ez.no/Requests/Contact-Sales'
);
INSERT INTO ezurl (
  created,
  id,
  is_valid,
  last_checked,
  modified,
  original_url_md5,
  url
) VALUES (
  1301057834,
  12,
  1,
  0,
  1301057834,
  '6d8c164dd30423d9dcbc3fae1d64e25c',
  'http://ez.no/eZPublish/Training-and-Certification'
);
INSERT INTO ezurl (
  created,
  id,
  is_valid,
  last_checked,
  modified,
  original_url_md5,
  url
) VALUES (
  1301057836,
  13,
  1,
  0,
  1301057836,
  'b13f5ff5cdcad2a4efb461e4edf6f718',
  'http://ez.no/Demos-videos/eZ-Publish-Administration-Interface-Video-Tutorial'
);
INSERT INTO ezurl (
  created,
  id,
  is_valid,
  last_checked,
  modified,
  original_url_md5,
  url
) VALUES (
  1301057836,
  14,
  1,
  0,
  1301057836,
  '7b133bbdf1d039979a973e5a697e3743',
  'http://ez.no/Demos-videos/eZ-Publish-Online-Editor-Video-Tutorial'
);
INSERT INTO ezurl (
  created,
  id,
  is_valid,
  last_checked,
  modified,
  original_url_md5,
  url
) VALUES (
  1301057836,
  15,
  1,
  0,
  1301057836,
  '4e75c83ab35d461f109ec959aa1c5e1d',
  'http://ez.no/Demos-videos/eZ-Flow-Video-Tutorial'
);
INSERT INTO ezurl (
  created,
  id,
  is_valid,
  last_checked,
  modified,
  original_url_md5,
  url
) VALUES (
  1301057836,
  16,
  1,
  0,
  1301057836,
  '215310c57a3d54ef1356c20855510357',
  'http://share.ez.no/learn/ez-publish/an-introduction-to-developing-ez-publish-extensions'
);
INSERT INTO ezurl (
  created,
  id,
  is_valid,
  last_checked,
  modified,
  original_url_md5,
  url
) VALUES (
  1301057836,
  17,
  1,
  0,
  1301057836,
  '9ba078c54f33985da5bd1348a8f39741',
  'http://share.ez.no/learn/ez-publish/creating-a-simple-custom-workflow-event'
);
INSERT INTO ezurl (
  created,
  id,
  is_valid,
  last_checked,
  modified,
  original_url_md5,
  url
) VALUES (
  1301057836,
  18,
  1,
  0,
  1301057836,
  'eb3d19c36acbd41176094024d8fccfd5',
  'http://www.slideshare.net/ezcommunity/ole-marius-smestad-rest-api-how-to-turn-ez-publish-into-a-multichannel-machine'
);
INSERT INTO ezurl (
  created,
  id,
  is_valid,
  last_checked,
  modified,
  original_url_md5,
  url
) VALUES (
  1301057836,
  19,
  1,
  0,
  1301057836,
  '1fea0fead02dfc550fbefa5c17acc94f',
  'http://www.slideshare.net/BertrandDunogier/presentation-winter-conference-2011-e-z-asynchronous-publishing'
);
INSERT INTO ezurl (
  created,
  id,
  is_valid,
  last_checked,
  modified,
  original_url_md5,
  url
) VALUES (
  1301057836,
  20,
  1,
  0,
  1301057836,
  'af8f8bdc5fac2f3ada6ad337adab04cb',
  'http://doc.ez.no/eZ-Publish/Upgrading/Upgrading-to-4.5'
);
INSERT INTO ezurl (
  created,
  id,
  is_valid,
  last_checked,
  modified,
  original_url_md5,
  url
) VALUES (
  1301057836,
  21,
  1,
  0,
  1301057836,
  '3c6d6cfc2642951e9a946b697f84a306',
  'http://share.ez.no/learn'
);
INSERT INTO ezurl (
  created,
  id,
  is_valid,
  last_checked,
  modified,
  original_url_md5,
  url
) VALUES (
  1301057836,
  22,
  1,
  0,
  1301057836,
  'ac3ba54b44950b2d77fa42cc57dab914',
  'http://ez.no/Demos-videos'
);

INSERT INTO ezurl_object_link (
  contentobject_attribute_id,
  contentobject_attribute_version,
  url_id
) VALUES (
  104,
  6,
  9
);
INSERT INTO ezurl_object_link (
  contentobject_attribute_id,
  contentobject_attribute_version,
  url_id
) VALUES (
  104,
  6,
  4
);
INSERT INTO ezurl_object_link (
  contentobject_attribute_id,
  contentobject_attribute_version,
  url_id
) VALUES (
  104,
  6,
  10
);
INSERT INTO ezurl_object_link (
  contentobject_attribute_id,
  contentobject_attribute_version,
  url_id
) VALUES (
  104,
  6,
  11
);
INSERT INTO ezurl_object_link (
  contentobject_attribute_id,
  contentobject_attribute_version,
  url_id
) VALUES (
  104,
  6,
  12
);
INSERT INTO ezurl_object_link (
  contentobject_attribute_id,
  contentobject_attribute_version,
  url_id
) VALUES (
  104,
  6,
  8
);
INSERT INTO ezurl_object_link (
  contentobject_attribute_id,
  contentobject_attribute_version,
  url_id
) VALUES (
  104,
  6,
  13
);
INSERT INTO ezurl_object_link (
  contentobject_attribute_id,
  contentobject_attribute_version,
  url_id
) VALUES (
  104,
  6,
  14
);
INSERT INTO ezurl_object_link (
  contentobject_attribute_id,
  contentobject_attribute_version,
  url_id
) VALUES (
  104,
  6,
  15
);
INSERT INTO ezurl_object_link (
  contentobject_attribute_id,
  contentobject_attribute_version,
  url_id
) VALUES (
  104,
  6,
  16
);
INSERT INTO ezurl_object_link (
  contentobject_attribute_id,
  contentobject_attribute_version,
  url_id
) VALUES (
  104,
  6,
  17
);
INSERT INTO ezurl_object_link (
  contentobject_attribute_id,
  contentobject_attribute_version,
  url_id
) VALUES (
  104,
  6,
  18
);
INSERT INTO ezurl_object_link (
  contentobject_attribute_id,
  contentobject_attribute_version,
  url_id
) VALUES (
  104,
  6,
  19
);
INSERT INTO ezurl_object_link (
  contentobject_attribute_id,
  contentobject_attribute_version,
  url_id
) VALUES (
  104,
  6,
  20
);
INSERT INTO ezurl_object_link (
  contentobject_attribute_id,
  contentobject_attribute_version,
  url_id
) VALUES (
  104,
  6,
  21
);
INSERT INTO ezurl_object_link (
  contentobject_attribute_id,
  contentobject_attribute_version,
  url_id
) VALUES (
  104,
  6,
  22
);

INSERT INTO ezurlalias (
  destination_url,
  forward_to_id,
  id,
  is_imported,
  is_internal,
  is_wildcard,
  source_md5,
  source_url
) VALUES (
  'content/view/full/2',
  0,
  12,
  1,
  1,
  0,
  'd41d8cd98f00b204e9800998ecf8427e',
  ''
);
INSERT INTO ezurlalias (
  destination_url,
  forward_to_id,
  id,
  is_imported,
  is_internal,
  is_wildcard,
  source_md5,
  source_url
) VALUES (
  'content/view/full/5',
  0,
  13,
  1,
  1,
  0,
  '9bc65c2abec141778ffaa729489f3e87',
  'users'
);
INSERT INTO ezurlalias (
  destination_url,
  forward_to_id,
  id,
  is_imported,
  is_internal,
  is_wildcard,
  source_md5,
  source_url
) VALUES (
  'content/view/full/12',
  0,
  15,
  1,
  1,
  0,
  '02d4e844e3a660857a3f81585995ffe1',
  'users/guest_accounts'
);
INSERT INTO ezurlalias (
  destination_url,
  forward_to_id,
  id,
  is_imported,
  is_internal,
  is_wildcard,
  source_md5,
  source_url
) VALUES (
  'content/view/full/13',
  0,
  16,
  1,
  1,
  0,
  '1b1d79c16700fd6003ea7be233e754ba',
  'users/administrator_users'
);
INSERT INTO ezurlalias (
  destination_url,
  forward_to_id,
  id,
  is_imported,
  is_internal,
  is_wildcard,
  source_md5,
  source_url
) VALUES (
  'content/view/full/14',
  0,
  17,
  1,
  1,
  0,
  '0bb9dd665c96bbc1cf36b79180786dea',
  'users/editors'
);
INSERT INTO ezurlalias (
  destination_url,
  forward_to_id,
  id,
  is_imported,
  is_internal,
  is_wildcard,
  source_md5,
  source_url
) VALUES (
  'content/view/full/15',
  0,
  18,
  1,
  1,
  0,
  'f1305ac5f327a19b451d82719e0c3f5d',
  'users/administrator_users/administrator_user'
);
INSERT INTO ezurlalias (
  destination_url,
  forward_to_id,
  id,
  is_imported,
  is_internal,
  is_wildcard,
  source_md5,
  source_url
) VALUES (
  'content/view/full/43',
  0,
  20,
  1,
  1,
  0,
  '62933a2951ef01f4eafd9bdf4d3cd2f0',
  'media'
);
INSERT INTO ezurlalias (
  destination_url,
  forward_to_id,
  id,
  is_imported,
  is_internal,
  is_wildcard,
  source_md5,
  source_url
) VALUES (
  'content/view/full/44',
  0,
  21,
  1,
  1,
  0,
  '3ae1aac958e1c82013689d917d34967a',
  'users/anonymous_users'
);
INSERT INTO ezurlalias (
  destination_url,
  forward_to_id,
  id,
  is_imported,
  is_internal,
  is_wildcard,
  source_md5,
  source_url
) VALUES (
  'content/view/full/45',
  0,
  22,
  1,
  1,
  0,
  'aad93975f09371695ba08292fd9698db',
  'users/anonymous_users/anonymous_user'
);
INSERT INTO ezurlalias (
  destination_url,
  forward_to_id,
  id,
  is_imported,
  is_internal,
  is_wildcard,
  source_md5,
  source_url
) VALUES (
  'content/view/full/48',
  0,
  25,
  1,
  1,
  0,
  'a0f848942ce863cf53c0fa6cc684007d',
  'setup'
);
INSERT INTO ezurlalias (
  destination_url,
  forward_to_id,
  id,
  is_imported,
  is_internal,
  is_wildcard,
  source_md5,
  source_url
) VALUES (
  'content/view/full/50',
  0,
  27,
  1,
  1,
  0,
  'c60212835de76414f9bfd21eecb8f221',
  'foo_bar_folder/images/vbanner'
);
INSERT INTO ezurlalias (
  destination_url,
  forward_to_id,
  id,
  is_imported,
  is_internal,
  is_wildcard,
  source_md5,
  source_url
) VALUES (
  'content/view/full/51',
  0,
  28,
  1,
  1,
  0,
  '38985339d4a5aadfc41ab292b4527046',
  'media/images'
);
INSERT INTO ezurlalias (
  destination_url,
  forward_to_id,
  id,
  is_imported,
  is_internal,
  is_wildcard,
  source_md5,
  source_url
) VALUES (
  'content/view/full/52',
  0,
  29,
  1,
  1,
  0,
  'ad5a8c6f6aac3b1b9df267fe22e7aef6',
  'media/files'
);
INSERT INTO ezurlalias (
  destination_url,
  forward_to_id,
  id,
  is_imported,
  is_internal,
  is_wildcard,
  source_md5,
  source_url
) VALUES (
  'content/view/full/53',
  0,
  30,
  1,
  1,
  0,
  '562a0ac498571c6c3529173184a2657c',
  'media/multimedia'
);
INSERT INTO ezurlalias (
  destination_url,
  forward_to_id,
  id,
  is_imported,
  is_internal,
  is_wildcard,
  source_md5,
  source_url
) VALUES (
  'content/view/full/54',
  0,
  31,
  1,
  1,
  0,
  'e501fe6c81ed14a5af2b322d248102d8',
  'setup/common_ini_settings'
);
INSERT INTO ezurlalias (
  destination_url,
  forward_to_id,
  id,
  is_imported,
  is_internal,
  is_wildcard,
  source_md5,
  source_url
) VALUES (
  'content/view/full/56',
  0,
  32,
  1,
  1,
  0,
  '2dd3db5dc7122ea5f3ee539bb18fe97d',
  'design/ez_publish'
);
INSERT INTO ezurlalias (
  destination_url,
  forward_to_id,
  id,
  is_imported,
  is_internal,
  is_wildcard,
  source_md5,
  source_url
) VALUES (
  'content/view/full/58',
  0,
  33,
  1,
  1,
  0,
  '31c13f47ad87dd7baa2d558a91e0fbb9',
  'design'
);

INSERT INTO ezurlalias_ml (
  action,
  action_type,
  alias_redirects,
  id,
  is_alias,
  is_original,
  lang_mask,
  link,
  parent,
  text,
  text_md5
) VALUES (
  'nop:',
  'nop',
  1,
  14,
  0,
  0,
  1,
  14,
  0,
  'foo_bar_folder',
  '0288b6883046492fa92e4a84eb67acc9'
);
INSERT INTO ezurlalias_ml (
  action,
  action_type,
  alias_redirects,
  id,
  is_alias,
  is_original,
  lang_mask,
  link,
  parent,
  text,
  text_md5
) VALUES (
  'eznode:58',
  'eznode',
  1,
  25,
  0,
  1,
  3,
  25,
  0,
  'Design',
  '31c13f47ad87dd7baa2d558a91e0fbb9'
);
INSERT INTO ezurlalias_ml (
  action,
  action_type,
  alias_redirects,
  id,
  is_alias,
  is_original,
  lang_mask,
  link,
  parent,
  text,
  text_md5
) VALUES (
  'eznode:48',
  'eznode',
  1,
  13,
  0,
  1,
  3,
  13,
  0,
  'Setup2',
  '475e97c0146bfb1c490339546d9e72ee'
);
INSERT INTO ezurlalias_ml (
  action,
  action_type,
  alias_redirects,
  id,
  is_alias,
  is_original,
  lang_mask,
  link,
  parent,
  text,
  text_md5
) VALUES (
  'nop:',
  'nop',
  1,
  17,
  0,
  0,
  1,
  17,
  0,
  'media2',
  '50e2736330de124f6edea9b008556fe6'
);
INSERT INTO ezurlalias_ml (
  action,
  action_type,
  alias_redirects,
  id,
  is_alias,
  is_original,
  lang_mask,
  link,
  parent,
  text,
  text_md5
) VALUES (
  'eznode:43',
  'eznode',
  1,
  9,
  0,
  1,
  3,
  9,
  0,
  'Media',
  '62933a2951ef01f4eafd9bdf4d3cd2f0'
);
INSERT INTO ezurlalias_ml (
  action,
  action_type,
  alias_redirects,
  id,
  is_alias,
  is_original,
  lang_mask,
  link,
  parent,
  text,
  text_md5
) VALUES (
  'nop:',
  'nop',
  1,
  21,
  0,
  0,
  1,
  21,
  0,
  'setup3',
  '732cefcf28bf4547540609fb1a786a30'
);
INSERT INTO ezurlalias_ml (
  action,
  action_type,
  alias_redirects,
  id,
  is_alias,
  is_original,
  lang_mask,
  link,
  parent,
  text,
  text_md5
) VALUES (
  'nop:',
  'nop',
  1,
  3,
  0,
  0,
  1,
  3,
  0,
  'users2',
  '86425c35a33507d479f71ade53a669aa'
);
INSERT INTO ezurlalias_ml (
  action,
  action_type,
  alias_redirects,
  id,
  is_alias,
  is_original,
  lang_mask,
  link,
  parent,
  text,
  text_md5
) VALUES (
  'eznode:5',
  'eznode',
  1,
  2,
  0,
  1,
  3,
  2,
  0,
  'Users',
  '9bc65c2abec141778ffaa729489f3e87'
);
INSERT INTO ezurlalias_ml (
  action,
  action_type,
  alias_redirects,
  id,
  is_alias,
  is_original,
  lang_mask,
  link,
  parent,
  text,
  text_md5
) VALUES (
  'eznode:2',
  'eznode',
  1,
  1,
  0,
  1,
  3,
  1,
  0,
  '',
  'd41d8cd98f00b204e9800998ecf8427e'
);
INSERT INTO ezurlalias_ml (
  action,
  action_type,
  alias_redirects,
  id,
  is_alias,
  is_original,
  lang_mask,
  link,
  parent,
  text,
  text_md5
) VALUES (
  'eznode:14',
  'eznode',
  1,
  6,
  0,
  1,
  3,
  6,
  2,
  'Editors',
  'a147e136bfa717592f2bd70bd4b53b17'
);
INSERT INTO ezurlalias_ml (
  action,
  action_type,
  alias_redirects,
  id,
  is_alias,
  is_original,
  lang_mask,
  link,
  parent,
  text,
  text_md5
) VALUES (
  'eznode:44',
  'eznode',
  1,
  10,
  0,
  1,
  3,
  10,
  2,
  'Anonymous-Users',
  'c2803c3fa1b0b5423237b4e018cae755'
);
INSERT INTO ezurlalias_ml (
  action,
  action_type,
  alias_redirects,
  id,
  is_alias,
  is_original,
  lang_mask,
  link,
  parent,
  text,
  text_md5
) VALUES (
  'eznode:12',
  'eznode',
  1,
  4,
  0,
  1,
  3,
  4,
  2,
  'Guest-accounts',
  'e57843d836e3af8ab611fde9e2139b3a'
);
INSERT INTO ezurlalias_ml (
  action,
  action_type,
  alias_redirects,
  id,
  is_alias,
  is_original,
  lang_mask,
  link,
  parent,
  text,
  text_md5
) VALUES (
  'eznode:13',
  'eznode',
  1,
  5,
  0,
  1,
  3,
  5,
  2,
  'Administrator-users',
  'f89fad7f8a3abc8c09e1deb46a420007'
);
INSERT INTO ezurlalias_ml (
  action,
  action_type,
  alias_redirects,
  id,
  is_alias,
  is_original,
  lang_mask,
  link,
  parent,
  text,
  text_md5
) VALUES (
  'nop:',
  'nop',
  1,
  11,
  0,
  0,
  1,
  11,
  3,
  'anonymous_users2',
  '505e93077a6dde9034ad97a14ab022b1'
);
INSERT INTO ezurlalias_ml (
  action,
  action_type,
  alias_redirects,
  id,
  is_alias,
  is_original,
  lang_mask,
  link,
  parent,
  text,
  text_md5
) VALUES (
  'eznode:12',
  'eznode',
  1,
  26,
  0,
  0,
  1,
  4,
  3,
  'guest_accounts',
  '70bb992820e73638731aa8de79b3329e'
);
INSERT INTO ezurlalias_ml (
  action,
  action_type,
  alias_redirects,
  id,
  is_alias,
  is_original,
  lang_mask,
  link,
  parent,
  text,
  text_md5
) VALUES (
  'eznode:14',
  'eznode',
  1,
  29,
  0,
  0,
  1,
  6,
  3,
  'editors',
  'a147e136bfa717592f2bd70bd4b53b17'
);
INSERT INTO ezurlalias_ml (
  action,
  action_type,
  alias_redirects,
  id,
  is_alias,
  is_original,
  lang_mask,
  link,
  parent,
  text,
  text_md5
) VALUES (
  'nop:',
  'nop',
  1,
  7,
  0,
  0,
  1,
  7,
  3,
  'administrator_users2',
  'a7da338c20bf65f9f789c87296379c2a'
);
INSERT INTO ezurlalias_ml (
  action,
  action_type,
  alias_redirects,
  id,
  is_alias,
  is_original,
  lang_mask,
  link,
  parent,
  text,
  text_md5
) VALUES (
  'eznode:13',
  'eznode',
  1,
  27,
  0,
  0,
  1,
  5,
  3,
  'administrator_users',
  'aeb8609aa933b0899aa012c71139c58c'
);
INSERT INTO ezurlalias_ml (
  action,
  action_type,
  alias_redirects,
  id,
  is_alias,
  is_original,
  lang_mask,
  link,
  parent,
  text,
  text_md5
) VALUES (
  'eznode:44',
  'eznode',
  1,
  30,
  0,
  0,
  1,
  10,
  3,
  'anonymous_users',
  'e9e5ad0c05ee1a43715572e5cc545926'
);
INSERT INTO ezurlalias_ml (
  action,
  action_type,
  alias_redirects,
  id,
  is_alias,
  is_original,
  lang_mask,
  link,
  parent,
  text,
  text_md5
) VALUES (
  'eznode:15',
  'eznode',
  1,
  8,
  0,
  1,
  3,
  8,
  5,
  'Administrator-User',
  '5a9d7b0ec93173ef4fedee023209cb61'
);
INSERT INTO ezurlalias_ml (
  action,
  action_type,
  alias_redirects,
  id,
  is_alias,
  is_original,
  lang_mask,
  link,
  parent,
  text,
  text_md5
) VALUES (
  'eznode:15',
  'eznode',
  1,
  28,
  0,
  0,
  0,
  8,
  7,
  'administrator_user',
  'a3cca2de936df1e2f805710399989971'
);
INSERT INTO ezurlalias_ml (
  action,
  action_type,
  alias_redirects,
  id,
  is_alias,
  is_original,
  lang_mask,
  link,
  parent,
  text,
  text_md5
) VALUES (
  'eznode:53',
  'eznode',
  1,
  20,
  0,
  1,
  3,
  20,
  9,
  'Multimedia',
  '2e5bc8831f7ae6a29530e7f1bbf2de9c'
);
INSERT INTO ezurlalias_ml (
  action,
  action_type,
  alias_redirects,
  id,
  is_alias,
  is_original,
  lang_mask,
  link,
  parent,
  text,
  text_md5
) VALUES (
  'eznode:52',
  'eznode',
  1,
  19,
  0,
  1,
  3,
  19,
  9,
  'Files',
  '45b963397aa40d4a0063e0d85e4fe7a1'
);
INSERT INTO ezurlalias_ml (
  action,
  action_type,
  alias_redirects,
  id,
  is_alias,
  is_original,
  lang_mask,
  link,
  parent,
  text,
  text_md5
) VALUES (
  'eznode:51',
  'eznode',
  1,
  18,
  0,
  1,
  3,
  18,
  9,
  'Images',
  '59b514174bffe4ae402b3d63aad79fe0'
);
INSERT INTO ezurlalias_ml (
  action,
  action_type,
  alias_redirects,
  id,
  is_alias,
  is_original,
  lang_mask,
  link,
  parent,
  text,
  text_md5
) VALUES (
  'eznode:45',
  'eznode',
  1,
  12,
  0,
  1,
  3,
  12,
  10,
  'Anonymous-User',
  'ccb62ebca03a31272430bc414bd5cd5b'
);
INSERT INTO ezurlalias_ml (
  action,
  action_type,
  alias_redirects,
  id,
  is_alias,
  is_original,
  lang_mask,
  link,
  parent,
  text,
  text_md5
) VALUES (
  'eznode:45',
  'eznode',
  1,
  31,
  0,
  0,
  1,
  12,
  11,
  'anonymous_user',
  'c593ec85293ecb0e02d50d4c5c6c20eb'
);
INSERT INTO ezurlalias_ml (
  action,
  action_type,
  alias_redirects,
  id,
  is_alias,
  is_original,
  lang_mask,
  link,
  parent,
  text,
  text_md5
) VALUES (
  'eznode:54',
  'eznode',
  1,
  22,
  0,
  1,
  2,
  22,
  13,
  'Common-INI-settings',
  '4434993ac013ae4d54bb1f51034d6401'
);
INSERT INTO ezurlalias_ml (
  action,
  action_type,
  alias_redirects,
  id,
  is_alias,
  is_original,
  lang_mask,
  link,
  parent,
  text,
  text_md5
) VALUES (
  'nop:',
  'nop',
  1,
  15,
  0,
  0,
  1,
  15,
  14,
  'images',
  '59b514174bffe4ae402b3d63aad79fe0'
);
INSERT INTO ezurlalias_ml (
  action,
  action_type,
  alias_redirects,
  id,
  is_alias,
  is_original,
  lang_mask,
  link,
  parent,
  text,
  text_md5
) VALUES (
  'eznode:50',
  'eznode',
  1,
  16,
  0,
  1,
  2,
  16,
  15,
  'vbanner',
  'c54e2d1b93642e280bdc5d99eab2827d'
);
INSERT INTO ezurlalias_ml (
  action,
  action_type,
  alias_redirects,
  id,
  is_alias,
  is_original,
  lang_mask,
  link,
  parent,
  text,
  text_md5
) VALUES (
  'eznode:53',
  'eznode',
  1,
  34,
  0,
  0,
  1,
  20,
  17,
  'multimedia',
  '2e5bc8831f7ae6a29530e7f1bbf2de9c'
);
INSERT INTO ezurlalias_ml (
  action,
  action_type,
  alias_redirects,
  id,
  is_alias,
  is_original,
  lang_mask,
  link,
  parent,
  text,
  text_md5
) VALUES (
  'eznode:52',
  'eznode',
  1,
  33,
  0,
  0,
  1,
  19,
  17,
  'files',
  '45b963397aa40d4a0063e0d85e4fe7a1'
);
INSERT INTO ezurlalias_ml (
  action,
  action_type,
  alias_redirects,
  id,
  is_alias,
  is_original,
  lang_mask,
  link,
  parent,
  text,
  text_md5
) VALUES (
  'eznode:51',
  'eznode',
  1,
  32,
  0,
  0,
  1,
  18,
  17,
  'images',
  '59b514174bffe4ae402b3d63aad79fe0'
);
INSERT INTO ezurlalias_ml (
  action,
  action_type,
  alias_redirects,
  id,
  is_alias,
  is_original,
  lang_mask,
  link,
  parent,
  text,
  text_md5
) VALUES (
  'eznode:54',
  'eznode',
  1,
  35,
  0,
  0,
  1,
  22,
  21,
  'common_ini_settings',
  'e59d6834e86cee752ed841f9cd8d5baf'
);
INSERT INTO ezurlalias_ml (
  action,
  action_type,
  alias_redirects,
  id,
  is_alias,
  is_original,
  lang_mask,
  link,
  parent,
  text,
  text_md5
) VALUES (
  'eznode:56',
  'eznode',
  1,
  37,
  0,
  0,
  2,
  24,
  25,
  'eZ-publish',
  '10e4c3cb527fb9963258469986c16240'
);
INSERT INTO ezurlalias_ml (
  action,
  action_type,
  alias_redirects,
  id,
  is_alias,
  is_original,
  lang_mask,
  link,
  parent,
  text,
  text_md5
) VALUES (
  'eznode:56',
  'eznode',
  1,
  24,
  0,
  1,
  2,
  24,
  25,
  'Plain-site',
  '49a39d99a955d95aa5d636275656a07a'
);

INSERT INTO ezurlalias_ml_incr (
  id
) VALUES (
  1
);
INSERT INTO ezurlalias_ml_incr (
  id
) VALUES (
  2
);
INSERT INTO ezurlalias_ml_incr (
  id
) VALUES (
  3
);
INSERT INTO ezurlalias_ml_incr (
  id
) VALUES (
  4
);
INSERT INTO ezurlalias_ml_incr (
  id
) VALUES (
  5
);
INSERT INTO ezurlalias_ml_incr (
  id
) VALUES (
  6
);
INSERT INTO ezurlalias_ml_incr (
  id
) VALUES (
  7
);
INSERT INTO ezurlalias_ml_incr (
  id
) VALUES (
  8
);
INSERT INTO ezurlalias_ml_incr (
  id
) VALUES (
  9
);
INSERT INTO ezurlalias_ml_incr (
  id
) VALUES (
  10
);
INSERT INTO ezurlalias_ml_incr (
  id
) VALUES (
  11
);
INSERT INTO ezurlalias_ml_incr (
  id
) VALUES (
  12
);
INSERT INTO ezurlalias_ml_incr (
  id
) VALUES (
  13
);
INSERT INTO ezurlalias_ml_incr (
  id
) VALUES (
  14
);
INSERT INTO ezurlalias_ml_incr (
  id
) VALUES (
  15
);
INSERT INTO ezurlalias_ml_incr (
  id
) VALUES (
  16
);
INSERT INTO ezurlalias_ml_incr (
  id
) VALUES (
  17
);
INSERT INTO ezurlalias_ml_incr (
  id
) VALUES (
  18
);
INSERT INTO ezurlalias_ml_incr (
  id
) VALUES (
  19
);
INSERT INTO ezurlalias_ml_incr (
  id
) VALUES (
  20
);
INSERT INTO ezurlalias_ml_incr (
  id
) VALUES (
  21
);
INSERT INTO ezurlalias_ml_incr (
  id
) VALUES (
  22
);
INSERT INTO ezurlalias_ml_incr (
  id
) VALUES (
  24
);
INSERT INTO ezurlalias_ml_incr (
  id
) VALUES (
  25
);
INSERT INTO ezurlalias_ml_incr (
  id
) VALUES (
  26
);
INSERT INTO ezurlalias_ml_incr (
  id
) VALUES (
  27
);
INSERT INTO ezurlalias_ml_incr (
  id
) VALUES (
  28
);
INSERT INTO ezurlalias_ml_incr (
  id
) VALUES (
  29
);
INSERT INTO ezurlalias_ml_incr (
  id
) VALUES (
  30
);
INSERT INTO ezurlalias_ml_incr (
  id
) VALUES (
  31
);
INSERT INTO ezurlalias_ml_incr (
  id
) VALUES (
  32
);
INSERT INTO ezurlalias_ml_incr (
  id
) VALUES (
  33
);
INSERT INTO ezurlalias_ml_incr (
  id
) VALUES (
  34
);
INSERT INTO ezurlalias_ml_incr (
  id
) VALUES (
  35
);
INSERT INTO ezurlalias_ml_incr (
  id
) VALUES (
  36
);
INSERT INTO ezurlalias_ml_incr (
  id
) VALUES (
  37
);

INSERT INTO ezuser (
  contentobject_id,
  email,
  login,
  password_hash,
  password_hash_type
) VALUES (
  10,
  'nospam@ez.no',
  'anonymous',
  '4e6f6184135228ccd45f8233d72a0363',
  2
);
INSERT INTO ezuser (
  contentobject_id,
  email,
  login,
  password_hash,
  password_hash_type
) VALUES (
  14,
  'nospam@ez.no',
  'admin',
  'c78e3b0f3d9244ed8c6d1c29464bdff9',
  2
);

INSERT INTO ezuser_role (
  contentobject_id,
  id,
  limit_identifier,
  limit_value,
  role_id
) VALUES (
  12,
  25,
  '',
  '',
  2
);
INSERT INTO ezuser_role (
  contentobject_id,
  id,
  limit_identifier,
  limit_value,
  role_id
) VALUES (
  11,
  28,
  '',
  '',
  1
);
INSERT INTO ezuser_role (
  contentobject_id,
  id,
  limit_identifier,
  limit_value,
  role_id
) VALUES (
  42,
  31,
  '',
  '',
  1
);
INSERT INTO ezuser_role (
  contentobject_id,
  id,
  limit_identifier,
  limit_value,
  role_id
) VALUES (
  13,
  32,
  'Subtree',
  '/1/2/',
  3
);
INSERT INTO ezuser_role (
  contentobject_id,
  id,
  limit_identifier,
  limit_value,
  role_id
) VALUES (
  13,
  33,
  'Subtree',
  '/1/43/',
  3
);

INSERT INTO ezuser_setting (
  is_enabled,
  max_login,
  user_id
) VALUES (
  1,
  1000,
  10
);
INSERT INTO ezuser_setting (
  is_enabled,
  max_login,
  user_id
) VALUES (
  1,
  10,
  14
);

INSERT INTO ezuservisit (
  current_visit_timestamp,
  failed_login_attempts,
  last_visit_timestamp,
  login_count,
  user_id
) VALUES (
  1301057720,
  0,
  1301057720,
  0,
  14
);

INSERT INTO ezvattype (
  id,
  name,
  percentage
) VALUES (
  1,
  'Std',
  0
);

INSERT INTO ezworkflow_group (
  created,
  creator_id,
  id,
  modified,
  modifier_id,
  name
) VALUES (
  1024392098,
  14,
  1,
  1024392098,
  14,
  'Standard'
);