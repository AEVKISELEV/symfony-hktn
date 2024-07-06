package ai

const (
	TypeText    = "TEXT"
	TypePicture = "IMAGE"
)

type CallbackResponse struct {
	ID      string `json:"id"`
	Content string `json:"content"`
}
