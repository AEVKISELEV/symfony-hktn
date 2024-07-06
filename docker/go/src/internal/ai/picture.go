package ai

type PictureGenerator struct {
}

type PictureData struct {
	TextData
}

func NewPictureGenerator() *PictureGenerator {
	return &PictureGenerator{}
}
