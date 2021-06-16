// Code generated by protoc-gen-go. DO NOT EDIT.
// versions:
// 	protoc-gen-go v1.26.0
// 	protoc        v3.17.2
// source: home.proto

package controllers

import (
	_ "github.com/go-ex/proto-help/http"
	protoreflect "google.golang.org/protobuf/reflect/protoreflect"
	protoimpl "google.golang.org/protobuf/runtime/protoimpl"
	reflect "reflect"
	sync "sync"
)

const (
	// Verify that this generated code is sufficiently up-to-date.
	_ = protoimpl.EnforceVersion(20 - protoimpl.MinVersion)
	// Verify that runtime/protoimpl is sufficiently up-to-date.
	_ = protoimpl.EnforceVersion(protoimpl.MaxVersion - 20)
)

type HomeRequest struct {
	state         protoimpl.MessageState
	sizeCache     protoimpl.SizeCache
	unknownFields protoimpl.UnknownFields

	UserId int32 `protobuf:"varint,3,opt,name=user_id,json=userId,proto3" json:"user_id,omitempty"`
}

func (x *HomeRequest) Reset() {
	*x = HomeRequest{}
	if protoimpl.UnsafeEnabled {
		mi := &file_home_proto_msgTypes[0]
		ms := protoimpl.X.MessageStateOf(protoimpl.Pointer(x))
		ms.StoreMessageInfo(mi)
	}
}

func (x *HomeRequest) String() string {
	return protoimpl.X.MessageStringOf(x)
}

func (*HomeRequest) ProtoMessage() {}

func (x *HomeRequest) ProtoReflect() protoreflect.Message {
	mi := &file_home_proto_msgTypes[0]
	if protoimpl.UnsafeEnabled && x != nil {
		ms := protoimpl.X.MessageStateOf(protoimpl.Pointer(x))
		if ms.LoadMessageInfo() == nil {
			ms.StoreMessageInfo(mi)
		}
		return ms
	}
	return mi.MessageOf(x)
}

// Deprecated: Use HomeRequest.ProtoReflect.Descriptor instead.
func (*HomeRequest) Descriptor() ([]byte, []int) {
	return file_home_proto_rawDescGZIP(), []int{0}
}

func (x *HomeRequest) GetUserId() int32 {
	if x != nil {
		return x.UserId
	}
	return 0
}

type HomeResponse struct {
	state         protoimpl.MessageState
	sizeCache     protoimpl.SizeCache
	unknownFields protoimpl.UnknownFields
}

func (x *HomeResponse) Reset() {
	*x = HomeResponse{}
	if protoimpl.UnsafeEnabled {
		mi := &file_home_proto_msgTypes[1]
		ms := protoimpl.X.MessageStateOf(protoimpl.Pointer(x))
		ms.StoreMessageInfo(mi)
	}
}

func (x *HomeResponse) String() string {
	return protoimpl.X.MessageStringOf(x)
}

func (*HomeResponse) ProtoMessage() {}

func (x *HomeResponse) ProtoReflect() protoreflect.Message {
	mi := &file_home_proto_msgTypes[1]
	if protoimpl.UnsafeEnabled && x != nil {
		ms := protoimpl.X.MessageStateOf(protoimpl.Pointer(x))
		if ms.LoadMessageInfo() == nil {
			ms.StoreMessageInfo(mi)
		}
		return ms
	}
	return mi.MessageOf(x)
}

// Deprecated: Use HomeResponse.ProtoReflect.Descriptor instead.
func (*HomeResponse) Descriptor() ([]byte, []int) {
	return file_home_proto_rawDescGZIP(), []int{1}
}

var File_home_proto protoreflect.FileDescriptor

var file_home_proto_rawDesc = []byte{
	0x0a, 0x0a, 0x68, 0x6f, 0x6d, 0x65, 0x2e, 0x70, 0x72, 0x6f, 0x74, 0x6f, 0x12, 0x0b, 0x63, 0x6f,
	0x6e, 0x74, 0x72, 0x6f, 0x6c, 0x6c, 0x65, 0x72, 0x73, 0x1a, 0x0d, 0x6d, 0x65, 0x74, 0x68, 0x6f,
	0x64, 0x73, 0x2e, 0x70, 0x72, 0x6f, 0x74, 0x6f, 0x1a, 0x0d, 0x73, 0x65, 0x72, 0x76, 0x69, 0x63,
	0x65, 0x2e, 0x70, 0x72, 0x6f, 0x74, 0x6f, 0x22, 0x26, 0x0a, 0x0b, 0x48, 0x6f, 0x6d, 0x65, 0x52,
	0x65, 0x71, 0x75, 0x65, 0x73, 0x74, 0x12, 0x17, 0x0a, 0x07, 0x75, 0x73, 0x65, 0x72, 0x5f, 0x69,
	0x64, 0x18, 0x03, 0x20, 0x01, 0x28, 0x05, 0x52, 0x06, 0x75, 0x73, 0x65, 0x72, 0x49, 0x64, 0x22,
	0x0e, 0x0a, 0x0c, 0x48, 0x6f, 0x6d, 0x65, 0x52, 0x65, 0x73, 0x70, 0x6f, 0x6e, 0x73, 0x65, 0x32,
	0xab, 0x01, 0x0a, 0x04, 0x48, 0x6f, 0x6d, 0x65, 0x12, 0x4b, 0x0a, 0x05, 0x49, 0x6e, 0x64, 0x65,
	0x78, 0x12, 0x18, 0x2e, 0x63, 0x6f, 0x6e, 0x74, 0x72, 0x6f, 0x6c, 0x6c, 0x65, 0x72, 0x73, 0x2e,
	0x48, 0x6f, 0x6d, 0x65, 0x52, 0x65, 0x71, 0x75, 0x65, 0x73, 0x74, 0x1a, 0x19, 0x2e, 0x63, 0x6f,
	0x6e, 0x74, 0x72, 0x6f, 0x6c, 0x6c, 0x65, 0x72, 0x73, 0x2e, 0x48, 0x6f, 0x6d, 0x65, 0x52, 0x65,
	0x73, 0x70, 0x6f, 0x6e, 0x73, 0x65, 0x22, 0x0d, 0x82, 0xb5, 0x18, 0x09, 0x2f, 0x61, 0x70, 0x69,
	0x2f, 0x68, 0x6f, 0x6d, 0x65, 0x12, 0x4b, 0x0a, 0x05, 0x4c, 0x6f, 0x67, 0x69, 0x6e, 0x12, 0x18,
	0x2e, 0x63, 0x6f, 0x6e, 0x74, 0x72, 0x6f, 0x6c, 0x6c, 0x65, 0x72, 0x73, 0x2e, 0x48, 0x6f, 0x6d,
	0x65, 0x52, 0x65, 0x71, 0x75, 0x65, 0x73, 0x74, 0x1a, 0x19, 0x2e, 0x63, 0x6f, 0x6e, 0x74, 0x72,
	0x6f, 0x6c, 0x6c, 0x65, 0x72, 0x73, 0x2e, 0x48, 0x6f, 0x6d, 0x65, 0x52, 0x65, 0x73, 0x70, 0x6f,
	0x6e, 0x73, 0x65, 0x22, 0x0d, 0x82, 0xb5, 0x18, 0x09, 0x2f, 0x61, 0x70, 0x69, 0x2f, 0x68, 0x6f,
	0x6d, 0x65, 0x1a, 0x09, 0x82, 0xb5, 0x18, 0x05, 0x6c, 0x6f, 0x67, 0x69, 0x6e, 0x42, 0x0e, 0x5a,
	0x0c, 0x2f, 0x63, 0x6f, 0x6e, 0x74, 0x72, 0x6f, 0x6c, 0x6c, 0x65, 0x72, 0x73, 0x62, 0x06, 0x70,
	0x72, 0x6f, 0x74, 0x6f, 0x33,
}

var (
	file_home_proto_rawDescOnce sync.Once
	file_home_proto_rawDescData = file_home_proto_rawDesc
)

func file_home_proto_rawDescGZIP() []byte {
	file_home_proto_rawDescOnce.Do(func() {
		file_home_proto_rawDescData = protoimpl.X.CompressGZIP(file_home_proto_rawDescData)
	})
	return file_home_proto_rawDescData
}

var file_home_proto_msgTypes = make([]protoimpl.MessageInfo, 2)
var file_home_proto_goTypes = []interface{}{
	(*HomeRequest)(nil),  // 0: controllers.HomeRequest
	(*HomeResponse)(nil), // 1: controllers.HomeResponse
}
var file_home_proto_depIdxs = []int32{
	0, // 0: controllers.Home.Index:input_type -> controllers.HomeRequest
	0, // 1: controllers.Home.Login:input_type -> controllers.HomeRequest
	1, // 2: controllers.Home.Index:output_type -> controllers.HomeResponse
	1, // 3: controllers.Home.Login:output_type -> controllers.HomeResponse
	2, // [2:4] is the sub-list for method output_type
	0, // [0:2] is the sub-list for method input_type
	0, // [0:0] is the sub-list for extension type_name
	0, // [0:0] is the sub-list for extension extendee
	0, // [0:0] is the sub-list for field type_name
}

func init() { file_home_proto_init() }
func file_home_proto_init() {
	if File_home_proto != nil {
		return
	}
	if !protoimpl.UnsafeEnabled {
		file_home_proto_msgTypes[0].Exporter = func(v interface{}, i int) interface{} {
			switch v := v.(*HomeRequest); i {
			case 0:
				return &v.state
			case 1:
				return &v.sizeCache
			case 2:
				return &v.unknownFields
			default:
				return nil
			}
		}
		file_home_proto_msgTypes[1].Exporter = func(v interface{}, i int) interface{} {
			switch v := v.(*HomeResponse); i {
			case 0:
				return &v.state
			case 1:
				return &v.sizeCache
			case 2:
				return &v.unknownFields
			default:
				return nil
			}
		}
	}
	type x struct{}
	out := protoimpl.TypeBuilder{
		File: protoimpl.DescBuilder{
			GoPackagePath: reflect.TypeOf(x{}).PkgPath(),
			RawDescriptor: file_home_proto_rawDesc,
			NumEnums:      0,
			NumMessages:   2,
			NumExtensions: 0,
			NumServices:   1,
		},
		GoTypes:           file_home_proto_goTypes,
		DependencyIndexes: file_home_proto_depIdxs,
		MessageInfos:      file_home_proto_msgTypes,
	}.Build()
	File_home_proto = out.File
	file_home_proto_rawDesc = nil
	file_home_proto_goTypes = nil
	file_home_proto_depIdxs = nil
}
